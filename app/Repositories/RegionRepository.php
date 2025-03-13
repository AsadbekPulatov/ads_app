<?php

namespace App\Repositories;

use App\Models\Region;

class RegionRepository
{
    protected Region $model;

    public function __construct(Region $model){
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
