<?php

namespace Src\Shared\Domain\Services;

interface AuthorizationServiceInterface
{
    public function authorize(string $ability): bool;
}