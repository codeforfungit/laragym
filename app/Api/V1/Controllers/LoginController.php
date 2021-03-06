<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Services\User\UserAuthService;

class LoginController extends Controller
{
    /**
     * @var UserAuthService
     */
    protected $authService;
    /**
     * LoginController constructor.
     * @param UserAuthService $authService
     */
    public function __construct(UserAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $token = $this->authService->login([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'is_active' => 1,
        ]);

        return response()
            ->json([
                'status' => 'ok',
                'token' => $token,
                'expires_in' => auth()->guard()->factory()->getTTL() * 60
            ]);
    }
}
