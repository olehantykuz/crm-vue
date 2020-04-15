<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /** @var UserService  */
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), with(new RegisterRequest())->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $this->userService->create($request->all());

        return new JsonResponse('', 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), with(new LoginRequest())->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $credentials = request(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::guard('web')->logout();

        return new JsonResponse(null, 204);
    }

    /**
     * @return JsonResponse
     */
    public function user()
    {
        /** @var User $user */
        $user = Auth::user();

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }
}
