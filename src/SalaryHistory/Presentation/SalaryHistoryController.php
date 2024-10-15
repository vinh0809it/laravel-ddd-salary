<?php

namespace Src\SalaryHistory\Presentation;

use Src\Common\Presentation\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Src\Common\Application\DTOs\PageMetaDTO;
use Src\Common\Domain\Services\AuthorizationServiceInterface;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryDTO;
use Src\SalaryHistory\Application\DTOs\SalaryHistoryFilterDTO;
use Src\SalaryHistory\Application\DTOs\UpdateSalaryHistoryDTO;
use Src\SalaryHistory\Presentation\Requests\StoreSalaryHistoryRequest;
use Src\SalaryHistory\Application\UseCases\Commands\StoreSalaryHistoryCommand;
use Src\SalaryHistory\Application\UseCases\Commands\UpdateSalaryHistoryCommand;
use Src\SalaryHistory\Application\UseCases\Queries\GetSalaryHistoriesQuery;
use Src\SalaryHistory\Presentation\Requests\GetSalaryHistoryRequest;
use Src\SalaryHistory\Presentation\Requests\UpdateSalaryHistoryRequest;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SalaryHistoryController extends BaseController
{
    public function __construct(
        private AuthorizationServiceInterface $authorizationService,
        private StoreSalaryHistoryCommand $storeSalaryHistoryCommand,
        private UpdateSalaryHistoryCommand $updateSalaryHistoryCommand,
        private GetSalaryHistoriesQuery $getSalaryHistoriesQuery
    ) {
        // Pls ignore this auth, I'm not going to implement authentication for this app
        // Just mock it to let the authorizationService work.
        $user = UserEloquentModel::first();
        Auth::login($user);
    }

    /**
     * @param GetSalaryHistoryRequest $request
     * 
     * @return JsonResponse
     */
    public function get(GetSalaryHistoryRequest $request): JsonResponse
    {
        try {
            $this->authorizationService->authorize('salary_history.get');

            $filter = SalaryHistoryFilterDTO::fromRequest($request);
            $pageMeta = PageMetaDTO::fromRequest($request);

            $resultWithPageMeta = $this->getSalaryHistoriesQuery->handle($filter, $pageMeta);

            return $this->sendResponseWithPageMeta(
                result: $resultWithPageMeta->toResponse()
            );
        } catch (Throwable $e) {
            
            return $this->handleException($e);
        }
    }
    
    /**
     * @param StoreSalaryHistoryRequest $request
     * 
     * @return JsonResponse
     */
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

    /**
     * @param string $id
     * @param UpdateSalaryHistoryRequest $request
     * 
     * @return JsonResponse
     */
    public function update(string $id, UpdateSalaryHistoryRequest $request): JsonResponse
    {
        try {
            $this->authorizationService->authorize('salary_history.update');
            $updateSalaryHistoryDTO = UpdateSalaryHistoryDTO::fromRequest($request, $id);
           
            $this->updateSalaryHistoryCommand->execute($updateSalaryHistoryDTO);

            return $this->sendResponse(
                result: null, 
                message: '', 
                httpCode: Response::HTTP_CREATED, 
            );
        } catch (Throwable $e) {
            
            return $this->handleException($e);
        }
    }
}
