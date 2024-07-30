<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 一些操作被拒绝的异常状态码
 */
class OperationDeniedException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'code'    => 403,
            'message' => $this->getMessage()
        ], 403);
    }
}
