<?php

namespace App\Services;

use App\Repositories\ADRepository;

class ADService
{
    protected ADRepository $repository;

    public function __construct(ADRepository $repository){
        $this->repository = $repository;
    }

    public function list(array $filter){
        return $this->repository->list($filter);
    }

    public function store(array $data){
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update(array $data, $id){
        return $this->repository->update($data, $id);
    }

    public function destroy($id){
        return $this->repository->delete($id);
    }
}
