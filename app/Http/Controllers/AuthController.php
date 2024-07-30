<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @var User[] 可供登陆的模型
     */
    protected array $authModels = [
        'teacher' => Teacher::class,
        'student' => Student::class,
    ];

    /**
     * 获取登陆模型
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAuthMode(string $type): \Illuminate\Database\Eloquent\Builder
    {
        $model = $this->authModels[$type];

        return $model::query();
    }

    /**
     * 登陆鉴权
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $guard_type = $request->input('type');
        $credentials = $request->only('type', 'username', 'password');

        try {
            // 查找用户
            $user = $this->getAuthMode($guard_type)
                ->where('username', $credentials['username'])
                ->firstOrFail();

            // 验证密码是否正确
            if (!Hash::check($credentials['password'], $user->password)) {
                return $this->failure('Incorrect user name or password', 401);
            }

            // 返回 token
            return $this->successData([
                'token' => $user->createToken($guard_type)->accessToken
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failure('Incorrect user name or password', 401);
        }

    }

    /**
     * 验证当前登陆的用户（调试）
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = Auth::user();
        dd($user);
    }

}
