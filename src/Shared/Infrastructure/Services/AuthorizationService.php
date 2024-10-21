<?php
namespace Src\Shared\Infrastructure\Services;

use Illuminate\Support\Facades\Gate;
use Src\Shared\Domain\Services\IAuthorizationService;
use Src\Shared\Domain\Exceptions\UnauthorizedUserException;

class AuthorizationService implements IAuthorizationService
{
    public function authorize(string $ability): bool
    { 
        if(!Gate::allows($ability)) {
            throw new UnauthorizedUserException();
        }
        
        return true;
    }
}
