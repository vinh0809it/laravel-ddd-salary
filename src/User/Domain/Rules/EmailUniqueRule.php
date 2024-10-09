<?php

namespace Src\User\Domain\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Src\User\Domain\Repositories\IUserRepository;

class EmailUniqueRule implements ValidationRule
{

    public function __construct(protected IUserRepository $userRepository)
    {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->userRepository->emailExists($value)) {
            $fail('The :attribute is already taken!');
        }
    }
}
