<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

/**
 * Class BaseResponse
 */
class BaseResponse extends JsonResponse
{
    /**
     * Send a success response with optional message string or body
     *
     * @param  mixed|null  $data
     * @return JsonResponse
     */
    public function respond(mixed $data = null): JsonResponse
    {
        $response = [];
        if (is_string($data)) {
            $response['message'] = $data;
        } elseif ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $this->status());
    }
}
