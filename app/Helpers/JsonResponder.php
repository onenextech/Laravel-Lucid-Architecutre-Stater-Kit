<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Response;

class JsonResponder
{
    public static function respond($message, $status, $data = []): \Illuminate\Http\JsonResponse
    {
        $responseBody = collect(['message' => $message, 'data' => $data])->filter();

        return Response::json($responseBody, $status);
    }

    public static function success($message = 'Success', $data = []): \Illuminate\Http\JsonResponse
    {
        return self::respond($message, 200, $data);
    }

    public static function unauthorized($message = 'Unauthorized'): \Illuminate\Http\JsonResponse
    {
        return self::respond($message, 401);
    }

    public static function validationError($message, $data)
    {
        return self::respond($message, 422, $data);
    }

    public static function internalServerError($message = 'Internal Server Error', $data = []): \Illuminate\Http\JsonResponse
    {
        return self::respond($message, 500, $data);
    }

    public static function notFound($message = 'Not Found')
    {
        return self::respond($message, 404);
    }

    public static function noContent($message = 'No Content')
    {
        return self::respond($message, 204);
    }
}
