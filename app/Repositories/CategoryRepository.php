<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected Category $model;

    public function __construct(Category $model){
        $this->model = $model;
    }

    public function list()
    {
        $list = $this->model->query()->with('category')->get();
        return $list->toArray();
    }
}
