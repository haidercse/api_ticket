<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function successResponse($data = [], $message = 'Successful', $status_code = JsonResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'errors' => null,
            'data' => $data

        ], $status_code);
    }

    public function errorResponse($errors, $message = 'Data is invalid', $status_code = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => false,
            'data' => null,
            'message' => $message,
            'errors' => $errors,

        ], $status_code);
    }
}