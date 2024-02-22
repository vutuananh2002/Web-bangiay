<?php

namespace App\Traits;

trait ApiResponser
{
    /**
     * Return a success response.
     * 
     * @param mixed $data
     * @param string|null $message
     * @param int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successReponse($data, $message = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    /**
     * 
     * 
     * @param mixed $data
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successCreatedResponse($data, $message = 'Created Successfully.')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 201);
    }

    protected function badRequestResponse($message = '')
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 400);
    }

    protected function responseUserWithAccessTokenAndCookie($data, $accessToken, $cookie, $message = '', $status_code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'user' => $data,
                'access_token' => [
                    'token_type' => 'Bearer',
                    'token' => $accessToken,
                    'expires_at' => now()->addMinute(env('SANCTUM_ACCESS_TOKEN_EXPIRES')),
                ],
            ],
        ], $status_code)->cookie($cookie);
    }

    protected function responseWithAccessTokenAndCookie($accessToken, $cookie, $message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'access_token' => [
                    'token_type' => 'Bearer',
                    'token' => $accessToken,
                    'expires_at' => now()->addMinute(env('SANCTUM_ACCESS_TOKEN_EXPIRES')),
                ],
            ],
        ], 200)->cookie($cookie);
    }

    protected function accessDeniedResponse($message = 'Access Denied')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 403);
    }

    protected function noContentResponse($message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 204);
    }

    protected function notVerifiedResponse($accessToken, $message = '')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [
                'access_token' => [
                    'token_type' => 'Bearer',
                    'token' => $accessToken,
                    'expires_at' => now()->addMinute(env('SANCTUM_ACCESS_TOKEN_EXPIRES')),
                ],
            ],
        ], 403);
    }

    protected function unprocessableEntityResponse($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 422);
    }

    protected function notFoundResponse($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }

    protected function responseWithCookie($data, $cookie, $message = '', $statusCode = 200) 
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                $data,
            ],
        ], $statusCode)->cookie($cookie);
    }

    protected function accessTokenResponse($accessToken, $message = '', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'access_token' => [
                    'token_type' => 'Bearer',
                    'token' => $accessToken,
                    'expires_at' => now()->addMinute(env('SANCTUM_ACCESS_TOKEN_EXPIRES')),
                ],
            ]
        ], $statusCode);
    }
}
