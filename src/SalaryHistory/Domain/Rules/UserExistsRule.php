<?php

namespace Src\SalaryHistory\Domain\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Src\User\Domain\Services\UserService;

class UserExistsRule implements ValidationRule
{

    public function __construct(private UserService $userService)
    {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->userService->userExists($value)) {
            $fail('The user is not existed!');
        }
    }
}
