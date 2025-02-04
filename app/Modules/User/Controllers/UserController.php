<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Dtos\StoreUserDto;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserService  $userService) {}


    public function create(StoreUserRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        return $this->userService->create(StoreUserDto::fromArray($validatedData));
    }

    public function authenticate(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        return $this->userService->login($validatedData);
    }

    public function getUser(): JsonResponse
    {
        return $this->userService->getUser();
    }

    public function logout(): JsonResponse
    {
        return $this->userService->logout();
    }
}
