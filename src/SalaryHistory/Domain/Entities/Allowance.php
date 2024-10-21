<?php
namespace Src\SalaryHistory\Domain\Entities;

use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Src\Shared\Domain\Entity;

// Just a simple entity as an example how it works with aggregate root
class Allowance extends Entity
{
    public function __construct(
        private string $id,
        private string $salaryHistoryId,
        private string $allowanceType,
        private float $amount,
        private Currency $currency,
    ) {}

    // --- Setters ---
    public function setAdjustmentType(string $newAllowanceType): void
    {
        $this->allowanceType = $newAllowanceType;
    }
    
    public function setAmount(float $newAmount): void
    {
        $this->amount = $newAmount;
    }

    public function setCurrency(Currency $newCurrency): void
    {
        $this->currency = $newCurrency;
    }

    // --- Getters ---
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAllowanceType(): string
    {
        return $this->allowanceType;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'allowance_type' => $this->getAllowanceType(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency()->getCurrencyCode(),
        ];
    }
}
