<?php

namespace App\Services;

use App\Repositories\RegionRepository;

class RegionService
{
    protected RegionRepository $repository;

    public function __construct(RegionRepository $repository){
        $this->repository = $repository;
    }

    public function list(){
        return $this->repository->list();
    }
}
