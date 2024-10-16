<?php

namespace Src\SalaryHistory\Domain\Services\External;

interface IUserDomainService
{
    public function userExists(string $userId): bool;
}