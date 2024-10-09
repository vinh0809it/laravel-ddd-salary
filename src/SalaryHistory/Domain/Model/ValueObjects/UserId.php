<?php
namespace Src\SalaryHistory\Domain\Model\ValueObjects;

final class UserId
{
    public readonly ?string $value;

    public function __construct(?string $companyId)
    {
        $this->value = $companyId;
    }

    public function toString()
    {
        return $this->value;    
    }

    public function jsonSerialize(): ?int
    {
        return $this->value;
    }
}