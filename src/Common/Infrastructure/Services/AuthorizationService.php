<?php
namespace Src\Common\Infrastructure\Services;

use Illuminate\Support\Facades\Gate;
use Src\Common\Domain\Services\AuthorizationServiceInterface;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;

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
