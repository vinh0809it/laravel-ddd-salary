<?php

namespace Src\SalaryHistory\Infrastructure\Policies;

class SalaryHistoryPolicy
{
    public static function get(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function store(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function update(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function delete(): bool
    {   
        return auth()->user()?->is_admin ?? false;
    }
}