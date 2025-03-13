<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository){
        $this->repository = $repository;
    }

    public function list(){
        return $this->repository->list();
    }
}
