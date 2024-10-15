<?php
namespace Src\SalaryHistory\Domain\Entities;

use Src\Shared\Domain\ValueObjects\Date;
use Src\Shared\Domain\AggregateRoot;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Src\SalaryHistory\Domain\ValueObjects\Salary;

class SalaryHistory extends AggregateRoot
{
    public function __construct(
        private ?string $id,
        private string $userId,
        private Date $onDate,
        private Salary $salary,
        private Currency $currency,
        private string $note
    ) {}

    // --- Business logic methods ---

    public function setDate(Date $newDate): void
    {
        $this->onDate = $newDate;
    }
    
    public function setSalary(Salary $newSalary): void
    {
        $this->salary = $newSalary;
    }

    public function setCurrency(Currency $newCurrency): void
    {
        $this->currency = $newCurrency;
    }

    public function setNote(string $newNote): void
    {
        $this->note = $newNote;
    }

    // --- Getters ---
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }


    public function getOnDate(): Date
    {
        return $this->onDate;
    }

    public function getSalary(): Salary
    {
        return $this->salary;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'on_date' => $this->getOnDate()->format(),
            'salary' => $this->getSalary()->getAmount(),
            'currency' => $this->getCurrency()->getCurrencyCode(),
            'note' => $this->getNote()
        ];
    }
}
