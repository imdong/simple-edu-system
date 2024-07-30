<?php

namespace App\Exceptions;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * 是否需要响应 json
     *
     * @var bool
     */
    private bool $is_json = false;

    public function __construct(Container $container)
    {
        $this->is_json = request()->expectsJson() || request()->is('api/*');

        parent::__construct($container);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // 参数验证失败
        $this->renderable(function (\Illuminate\Validation\ValidationException $e) {
            if ($this->is_json) {
                return response()->json([
                    'code'    => 400,
                    'message' => $e->getMessage(),
                    'data'    => $e->errors(),
                ], 400);
            }
        });

        // 找不到页面
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException  $e) {
            if ($this->is_json) {
                return response()->json([
                    'code'    => 404,
                    'message' => $e->getMessage()
                ], 404);
            }
        });

        // 未登陆
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e) {
            if ($this->is_json) {
                return response()->json([
                    'code'    => 401,
                    'message' => $e->getMessage()
                ], 401);
            }
        });

        // 暂不处理
        $this->reportable(function (Throwable $e) {
            // dd($e);
        });
    }
}
