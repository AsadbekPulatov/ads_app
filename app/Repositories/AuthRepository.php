<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function register($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function login($data)
    {
        if (Auth::attempt(['email' => $data["email"], 'password' => $data["password"]])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('LaravelSanctumAuth')->plainTextToken;
            $success['email'] = $auth->email;
            $success['id'] = $auth->id;
            return $success;
        } else {
            return ["error" => [
                "message" => "Unauthorized",
                "code" => 401
            ]];
        }
    }
}
