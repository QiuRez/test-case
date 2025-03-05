<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Response\ErrorResponse;
use App\Response\SuccessEmptyResponse;
use App\Response\SuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return SuccessResponse::make(UserResource::collection(User::all()), 'User list');
    }

    public function show(User $user): JsonResponse
    {
        return SuccessResponse::make(UserResource::make($user), 'Show User');
    }


    public function destroy(UserDestroyRequest $request, User $user): JsonResponse
    {
        $user->forceDelete();
        return SuccessEmptyResponse::make('User success delete');
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());
        return SuccessResponse::make(UserResource::make($user), 'User success update');
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        if(User::firstWhere('email', '=', $validated['email'])) {
            return ErrorResponse::make('User alredy registed', SymfonyResponse::HTTP_CONFLICT);
        } else {
            $user = User::create($validated);

            return SuccessEmptyResponse::make('User success registed');
        }
    }
    public function login(UserLoginRequest $request): JsonResponse
    {
        if(Auth::guard('api')->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $user = User::firstWhere('email', '=', $request->get('email'));
            $token = $user->createToken('API TOKEN')->plainTextToken;
            return SuccessResponse::make(UserLoginResource::make(['user' => $user, 'token' => $token]), 'User success login');
        }

        return ErrorResponse::make('Incorrect email or password');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return SuccessEmptyResponse::make('Success logout');
    }
}
