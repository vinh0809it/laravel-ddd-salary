<?php

namespace Src\Shared\Domain\Services;

interface IAuthorizationService
{
    public function authorize(string $ability): bool;
}