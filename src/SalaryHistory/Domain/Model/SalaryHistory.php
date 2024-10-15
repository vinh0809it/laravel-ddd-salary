<?php
namespace Src\SalaryHistory\Domain\Model;

use Src\Common\Domain\ValueObjects\Date;
use Src\Common\Domain\AggregateRoot;
use Src\SalaryHistory\Domain\ValueObjects\Salary;

class SalaryHistory extends AggregateRoot
{
    public function __construct(
        public ?string $id,
        public string $userId,
        public Date $onDate,
        public Salary $salary,
        public ?string $note
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

    public function setNote(string $newNote): void
    {
        $this->note = $newNote;
    }

    // --- Getters ---
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOnDate(): Date
    {
        return $this->onDate;
    }

    public function getSalary(): Salary
    {
        return $this->salary;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->userId,
            'on_date' => $this->getOnDate()->format(),
            'salary' => $this->getSalary()->getAmount(),
            'note' => $this->note
        ];
    }
}
