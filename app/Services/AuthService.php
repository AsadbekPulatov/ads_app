<?php

namespace App\Services;

use App\Http\Requests\AuthRequest;
use App\Repositories\AuthRepository;

class AuthService
{
    protected AuthRepository $repository;

    public function __construct(AuthRepository $repository){
        $this->repository = $repository;
    }

    public function register($data)
    {
        return $this->repository->register($data);
    }

    public function login($data)
    {
        return $this->repository->login($data);
    }
}
