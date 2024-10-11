<?php

namespace Src\SalaryHistory\Presentation;

use Src\Common\Presentation\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Src\Common\Domain\Services\AuthorizationServiceInterface;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Presentation\Requests\StoreSalaryHistoryRequest;
use Src\SalaryHistory\Application\UseCases\Commands\StoreSalaryHistoryCommand;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SalaryHistoryController extends BaseController
{

    public function __construct(
        private AuthorizationServiceInterface $authorizationService,
        private StoreSalaryHistoryCommand $storeSalaryHistoryCommand
    ) {
        $user = UserEloquentModel::first();
        Auth::login($user);
    }

    public function store(StoreSalaryHistoryRequest $request): JsonResponse
    {
        try {
            $this->authorizationService->authorize('salary_history.store');
           
            $salaryHistoryDTO = SalaryHistoryDTO::fromRequest($request);
            $salaryHistory = $this->storeSalaryHistoryCommand->execute($salaryHistoryDTO);
            $response = SalaryHistoryDTO::toResponse($salaryHistory);

            return $this->sendResponse(
                result: $response, 
                message: '', 
                httpCode: Response::HTTP_CREATED, 
            );

        } catch (Throwable $e) {
            
            return $this->handleException($e);
        }
    }
}
