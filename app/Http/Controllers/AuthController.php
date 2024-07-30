<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 登陆鉴权
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $guard_name = $request->input('type');
        $credentials = $request->only('username', 'password');

        $provider = Auth::guard($guard_name)->getProvider();
        $user = $provider->retrieveByCredentials($credentials);

        // 失败否？
        if (!$user || !$provider->validateCredentials($user, $credentials)) {
            return $this->failure('Incorrect user name or password', 401);
        }

        // 返回 token
        return $this->successData([
            'token' => $user->createToken($guard_name)->accessToken
        ]);
    }

    /**
     * 验证当前登陆的用户（调试）
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = Auth::user();

        return $this->successData([
            'role' => $user->getUserRole(),
            'user' => $user,
        ]);
    }

}
