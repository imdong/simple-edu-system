<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class PayException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'code'    => $this->getCode(),
            'message' => $this->getMessage()
        ], $this->getCode());
    }
}
