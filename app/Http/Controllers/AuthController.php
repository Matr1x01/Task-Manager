<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function __construct(private readonly AuthService $authService)
    {}

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request->string('email'), $request->string('password'));
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    public function register(RegisterRequest $request)
    {
        return $this->authService->register($request->string('name'), $request->string('email'), $request->string('password'));
    }
}
