<?php

namespace Src\Common\Domain\Services;

interface AuthorizationServiceInterface
{
    public function authorize(string $ability): bool;
}