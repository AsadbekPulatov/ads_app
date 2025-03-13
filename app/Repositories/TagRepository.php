<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    protected Tag $model;

    public function __construct(Tag $model){
        $this->model = $model;
    }

    public function list()
    {
        return $this->model->query()->get()->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name,
            ];
        });
    }
}
