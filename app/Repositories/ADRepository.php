<?php

namespace App\Repositories;

use App\Models\Ad;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ADRepository
{
    protected Ad $model;

    public function __construct(Ad $model)
    {
        $this->model = $model;
    }

    public function list($filter)
    {
        $ads = $this->model->query()
            ->when(isset($filter['regions']), function ($query) use ($filter) {
                $query->whereHas('regions', function ($query) use ($filter) {
                    $query->whereIn('regions.id', $filter['regions']);
                });
            })
            ->when(isset($filter['categories']), function ($query) use ($filter) {
                $query->whereHas('categories', function ($query) use ($filter) {
                    $query->whereIn('categories.id', $filter['categories']);
                });
            })
            ->when(isset($filter['tags']), function ($query) use ($filter) {
                $query->whereHas('tags', function ($query) use ($filter) {
                    $query->whereIn('tags.id', $filter['tags']);
                });
            })
            ->when(isset($filter["price_from"]), function ($query) use ($filter) {
                $query->whereBetween('price', [$filter["price_from"], $filter["price_to"]]);
            })
            ->when(isset($filter["date_from"]), function ($query) use ($filter) {
                $query->whereBetween('created_at', [$filter["date_from"], $filter["date_to"]]);
            })
            ->when(isset($filter["search"]), function ($query) use ($filter) {
                $search = "%".$filter["search"]."%";
                $query->where('title', 'like', $search)->orWhere('description', 'like', $search);
            })
            ->when(isset($filter["order_column"]), function ($query) use ($filter) {
                $query->orderBy($filter["order_column"], $filter["order_type"]);
            })
            ->paginate($filter['per_page'] ?? 10);

        return $ads;
    }

    public function store(array $data)
    {
        $image = $data['image'];

        $filename = $image->storeAs('ads', $image->hashName(), 'public');

        $data['user_id'] = auth()->id();
        $data['image'] = $filename;

        $ad = $this->model->create($data);
        $ad->categories()->sync($data['categories']);
        $ad->regions()->sync($data['regions']);
        $ad->tags()->sync($data['tags']);

        return $ad;
    }

    public function getById($id)
    {
        return $this->model->query()
            ->with('regions', 'categories', 'tags')
            ->where('id', $id)->first();
    }

    public function show($id)
    {
        $ad = $this->getById($id);
        if (!isset($ad)) {
            return ["error" => [
                "message" => "Not found",
                "code" => 404
            ]];
        }
        $ad->views = $ad->views + 1;
        $ad->save();
        $path = URL::to('/') . '/storage/' . $ad->image;
        $ad = $ad->toArray();
        $ad["path"] = $path;
        return $ad;
    }

    public function update(array $data, $id)
    {
        $ad = $this->getById($id);

        if (!isset($ad)) {
            return ["error" => [
                "message" => "Not found",
                "code" => 404
            ]];
        }

        if (auth()->id() != $ad->user_id) {
            return ["error" => [
                "message" => "Forbidden",
                "code" => 403
            ]];
        }

        if (isset($data['image'])) {
            $image = $data['image'];

            $filename = $image->storeAs('ads', $image->hashName(), 'public');
            $data['image'] = $filename;
        }

        $ad->update($data);

        if (isset($data['categories'])) {
            $ad->categories()->sync($data['categories']);
        }
        if (isset($data['regions'])) {
            $ad->regions()->sync($data['regions']);
        }
        if (isset($data['tags'])) {
            $ad->tags()->sync($data['tags']);
        }

        return $ad;
    }

    public function delete($id){
        $ad = $this->getById($id);

        if (!isset($ad)) {
            return ["error" => [
                "message" => "Not found",
                "code" => 404
            ]];
        }

        if (auth()->id() != $ad->user_id) {
            return ["error" => [
                "message" => "Forbidden",
                "code" => 403
            ]];
        }

        $image = $ad->image;
        if (Storage::exists('public/ads/' . $image)){
            Storage::delete('public/ads/' . $image);
        }

        $ad->delete();

        return true;
    }
}
