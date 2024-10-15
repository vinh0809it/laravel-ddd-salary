<?php
namespace Src\Common\Presentation;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Src\Common\Domain\Exceptions\DomainException;
use Src\Common\Infrastructure\Laravel\Controller;
use Throwable;

class BaseController extends Controller
{
    protected function handleException(Throwable $e): JsonResponse
    {
        if ($e instanceof DomainException) {
            return $this->sendError(msg: $e->getMessage(), httpCode: $e->getHttpCode());
        }
        
        $returnMessage = env('APP_DEBUG') ? $e->getMessage() : 'Something went wrong.';
        return $this->sendError(msg: $returnMessage, httpCode: Response::HTTP_INTERNAL_SERVER_ERROR);
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
     * @param array|null $result
     * @param string $message
     * @param int $httpCode
     * 
     * @return JsonResponse
     */
    public function sendResponseWithPageMeta(?array $result = [], string $message = "", int $httpCode = Response::HTTP_OK): JsonResponse
    {
        $result['message'] = $message;
        return response()->json($result, $httpCode);
    }

    /**
     * @param string $msg
     * @param array|null $errors
     * @param int $httpCode
     * 
     * @return JsonResponse
     */
    public function sendError(string $msg, ?array $errors = [], int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $response['message'] = $msg;
        $response['data'] = $errors;

        return response()->json($response, $httpCode);
    }
}
