<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * 返回执行成功的响应
     *
     * @param string $message 响应消息
     * @param int $code 响应代码 如务必要,勿传此参
     *
     * @return JsonResponse
     */
    public function success(string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'code'    => $code,
            'message' => $message,
        ]);
    }

    /**
     * 执行成功切带数据返回
     *
     * @param array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection $data 响应数据 (必须为关联数组)
     * @param string $message 响应消息 如无必要,勿传此参
     * @param int $code 响应代码 如无必要,勿传此参
     *
     * @return JsonResponse
     */
    public function successData(mixed $data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    /**
     * 响应失败的请求 (正常情况下直接抛异常即可 不用走这里)
     *
     * @param string $message 消息描述
     * @param int $code 错误代码
     * @param array|null $data 相关附属数据 可空
     *
     * @return JsonResponse
     */
    public function failure(string $message = 'Failure', int $code = 500, array $data = null): JsonResponse
    {
        $result = [
            'code'    => $code,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $result['data'] = $data;
        }

        return response()->json($result);
    }
}
