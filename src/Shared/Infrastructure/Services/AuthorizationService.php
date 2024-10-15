<?php
namespace Src\Shared\Infrastructure\Services;

use Illuminate\Support\Facades\Gate;
use Src\Shared\Domain\Services\AuthorizationServiceInterface;
use Src\Shared\Domain\Exceptions\UnauthorizedUserException;

class AuthorizationService implements AuthorizationServiceInterface
{
    public function authorize(string $ability): bool
    { 
        if(!Gate::allows($ability)) {
            throw new UnauthorizedUserException();
        }
        
        return true;
    }
}
