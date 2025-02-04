<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\Dtos\StoreUserDto;
use App\Modules\User\Resources\UserResource;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    use ApiResponses;

    public function create(StoreUserDto $dto): JsonResponse
    {
        $userData = (array) $dto;

        if (isset($dto->picture)) {
            // store file in local storage
            $path = $dto->picture->store('public/user_pictures');
            //generate and save full url
            $userData['picture'] = Storage::url(str_replace('public/', '', $path));
        }

        $user = User::create($userData);

        $result = new UserResource($user);

        return $this->successApiResponse(
            "Registered successfully!",
            $result,
            Response::HTTP_CREATED
        );
    }

    public function login(array $validatedData): JsonResponse
    {
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return $this->notFoundApiResponse('Invalid credentials');
        }
        $userData = new UserResource($user);
        $token = $user->createToken('Login Token')->plainTextToken;

        return $this->successApiResponse(
            'Login successfully',
            ['token' => $token, 'user' => $userData,],
            Response::HTTP_ACCEPTED
        );
    }

    public function getUser(): JsonResponse
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);

        $userData = new UserResource($user);

        return $this->successApiResponse(
            "success",
            $userData,
            Response::HTTP_ACCEPTED
        );
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();
        return $this->successNoDataApiResponse('Logout successfully');
    }
}
