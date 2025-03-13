<?php

namespace App\Services;

use App\Repositories\TagRepository;

class TagService
{
    protected TagRepository $repository;

    public function __construct(TagRepository $repository){
        $this->repository = $repository;
    }

    public function list(){
        return $this->repository->list();
    }
}
