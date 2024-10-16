<?php

namespace Src\SalaryHistory\Domain\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Src\SalaryHistory\Domain\Services\External\IUserDomainService;

class UserExistsRule implements ValidationRule
{
    public function __construct(private IUserDomainService $userDomainService)
    {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->userDomainService->userExists($value)) {
            $fail('user_id.user_not_existed');
        }
    }
}
