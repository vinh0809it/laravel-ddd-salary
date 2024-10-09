<?php

namespace Src\User\Presentation;

use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\User\Application\UseCases\Commands\StoreUserCommand;
use Src\User\Application\UseCases\Commands\UpdateUserCommand;
use Src\Common\Domain\Services\AuthorizationServiceInterface;
use Src\Common\Infrastructure\Laravel\Controller;
use Src\User\Application\DTOs\UserDTO;
use Src\User\Application\UseCases\Commands\DeleteUserCommand;
use Src\User\Application\UseCases\Queries\GetUsersQuery;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Src\User\Presentation\Requests\StoreUserRequest;
use Src\User\Presentation\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
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

            return response()->json($response, Response::HTTP_OK);

        } catch (DomainException $domainException) {
            
            return response()->json(['errors' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {

            return response()->json(['errors' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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

            return response()->json($response, Response::HTTP_CREATED);

        } catch (DomainException $domainException) {
            
            return response()->json(['errors' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {

            return response()->json(['errors' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $result = $updateUserCommand->execute($userDTO);
            return response()->json($result, Response::HTTP_OK);

        } catch (DomainException $domainException) {
            
            return response()->json(['errors' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {

            return response()->json(['errors' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $result = $deleteUserCommand->execute($id);
            return response()->json($result, Response::HTTP_NO_CONTENT);
        } catch (DomainException $domainException) {
            
            return response()->json(['errors' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {

            return response()->json(['errors' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
