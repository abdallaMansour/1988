<?php

namespace App\Trait;

trait ApiResponse
{
    public function sendResponse($data, $status = 200)
    {
        $data = [
            'success' => $status === 200,
            'data' => $data,
        ];

        return response()->json($data, $status);
    }

    public function sendError($message, $status = 400)
    {
        $data = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($data, $status);
    }

    public function sendSuccess($message, $status = 200)
    {
        $data = [
            'success' => true,
            'message' => $message,
        ];

        return response()->json($data, $status);
    }
}