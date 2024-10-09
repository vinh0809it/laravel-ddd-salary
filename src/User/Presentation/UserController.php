<?php
namespace Src\User\Presentation;

use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Src\User\Application\UseCases\Commands\StoreUserCommand;
use Src\User\Application\UseCases\Commands\UpdateUserCommand;
use Src\Common\Domain\Services\AuthorizationServiceInterface;
use Src\Common\Presentation\BaseController;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Application\UseCases\Commands\DeleteUserCommand;
use Src\User\Application\UseCases\Queries\GetUsersQuery;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Src\User\Presentation\Requests\StoreUserRequest;
use Src\User\Presentation\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends BaseController
{

    public function __construct( private AuthorizationServiceInterface $authorizationService) {
        $user = UserEloquentModel::first();
        Auth::login($user);
    }

    /**
     * @param Request $request
     * @param GetUsersQuery $getUsersQuery
     * 
     * @return JsonResponse
     */
    public function get(Request $request, GetUsersQuery $getUsersQuery): JsonResponse
    {
        $this->authorizationService->authorize('user.get');

        try {
            $users = $getUsersQuery->handle($request->email, $request->name);
            $response = UserDTO::toResponse($users);

            return $this->sendResponse(
                result: $response,
            );

        } catch (Throwable $e) {
            
            return $this->handleException($e);
        }
    }

    /**
     * @param StoreUserRequest $request
     * @param StoreUserCommand $storeUserCommand
     * 
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request, StoreUserCommand $storeUserCommand): JsonResponse
    {
        try {
            $this->authorizationService->authorize('user.store');
           
            $userDTO = UserDTO::fromRequest($request);
            $user = $storeUserCommand->execute($userDTO);
            $response = UserDTO::toResponse($user);
          
            return $this->sendResponse(
                result: $response, 
                httpCode: Response::HTTP_CREATED, 
            );

        } catch (Throwable $e) {
            
            return $this->handleException($e);
        }
    }

    /**
     * @param int $id
     * @param UpdateUserRequest $request
     * @param UpdateUserCommand $updateUserCommand
     * 
     * @return JsonResponse
     */
    public function update(int $id, UpdateUserRequest $request, UpdateUserCommand $updateUserCommand): JsonResponse
    {
        $this->authorizationService->authorize('user.update');

        try {
            $userDTO = UserDTO::fromRequest($request, $id);
            $updateUserCommand->execute($userDTO);

            return $this->sendResponse();

        } catch (Throwable $e) {

            return $this->handleException($e);
        }
    }

    /**
     * @param int $id
     * @param DeleteUserCommand $deleteUserCommand
     * 
     * @return JsonResponse
     */
    public function destroy(int $id, DeleteUserCommand $deleteUserCommand): JsonResponse
    {
        $this->authorizationService->authorize('user.delete');

        try {
            $deleteUserCommand->execute($id);
            return $this->sendResponse(httpCode: Response::HTTP_NO_CONTENT);

        } catch (Throwable $e) {
            
            return $this->handleException($e);
        }
    }
}