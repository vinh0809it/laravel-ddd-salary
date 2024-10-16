<?php
namespace Src\SalaryHistory\Domain\Entities;

use Src\SalaryHistory\Domain\ValueObjects\Currency;

// Just a simple entity as an example how it works with aggregate root
class SalaryAdjustment
{
    public function __construct(
        private string $id,
        private string $salaryHistoryId,
        private string $adjustmentType,
        private float $amount,
        private Currency $currency,
    ) {}

    // --- Setters ---
    public function setAdjustmentType(string $newAdjustmentType): void
    {
        $this->adjustmentType = $newAdjustmentType;
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

    public function getAdjustmentType(): string
    {
        return $this->adjustmentType;
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
            'adjustment_type' => $this->getAdjustmentType(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency()->getCurrencyCode(),
        ];
    }
}
