<?php

namespace Src\Common\Presentation;

use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Src\Common\Domain\Exceptions\EntityNotFoundException;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Src\Common\Infrastructure\Laravel\Controller;
use Throwable;

class BaseController extends Controller
{
    protected function handleException(Throwable $e): JsonResponse
    {
        if ($e instanceof EntityNotFoundException) {
            return $this->sendError(msg: $e->getMessage(), httpCode: Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof UnauthorizedUserException) {
            return $this->sendError(msg: $e->getMessage(), httpCode: Response::HTTP_UNAUTHORIZED);
        }

        if ($e instanceof DomainException) {
            return $this->sendError(msg: $e->getMessage(), httpCode: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        return $this->sendError(msg: 'Something went wrong.', httpCode: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param array $result
     * @param string $message
     * @param int $httpCode
     * 
     * @return JsonResponse
     */
    public function sendResponse(?array $result = [], string $message = "", int $httpCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $httpCode);
    }

    /**
     * @param string $msg
     * @param array|null $errors
     * @param int $httpCode
     * 
     * @return JsonResponse
     */
    public function sendError(string $msg, ?array $errors = [], int $httpCode = 500): JsonResponse
    {
        $response['message'] = $msg;
        $response['data'] = $errors;

        return response()->json($response, $httpCode);
    }
}
