<?php

namespace Src\Common\Presentation;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Src\Common\Infrastructure\Laravel\Controller;

class BaseController extends Controller
{
    /**
     * @param array $result
     * @param string $message
     * @param int $httpCode
     * 
     * @return JsonResponse
     */
    public function sendResponse(array $result, string $message = "", int $httpCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $httpCode);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $httpCode
     * @return Response
     */
    public function sendError($error, $errorMessages = [], int $httpCode = 500)
    {
        $response = [
            'message' => $error,
        ];
        
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response($response, $httpCode);
    }
}
