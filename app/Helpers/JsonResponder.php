<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Response;

class JsonResponder{
    public static function respond($message, $status, $data = []): \Illuminate\Http\JsonResponse
    {
        $responseBody = collect(['message' => $message, 'data' => $data])->filter();
        return Response::json($responseBody, $status);
    }

    public static function unauthorized($message = 'Unauthorized'): \Illuminate\Http\JsonResponse
    {
        return self::respond($message, 401);
    }
}
