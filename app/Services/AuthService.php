<?php

namespace App\Services;

use App\Helpers\JsonResponder;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $email, string $password): JsonResponse
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return JsonResponder::respond('Unauthorized', 401);
        }

        $user = auth()->user();

        $token = $user->createToken('Personal Access Token')->accessToken;

        return JsonResponder::respond(data:['token' => $token, 'user' => UserResource::make($user)]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return JsonResponder::respond('Successfully logged out');
    }

    public function register(string $name, string $email, string $password): JsonResponse
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return JsonResponder::respond(data:['token' => $token, 'user' => UserResource::make($user)]);
    }

}
