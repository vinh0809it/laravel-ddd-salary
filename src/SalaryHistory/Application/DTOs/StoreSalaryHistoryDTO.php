<?php

namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Http\Request;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Src\SalaryHistory\Domain\ValueObjects\Salary;
use Src\Shared\Domain\ValueObjects\Date;

final class StoreSalaryHistoryDTO
{
    public function __construct(
        public string $userId, 
        public Date $onDate, 
        public Salary $salary, 
        public Currency $currency, 
        public ?string $id = null, 
        public ?string $note = null
    ){}

    /**
     * @param string|null $id
     * @param string $userId
     * @param string $onDate
     * @param float $salary
     * @param string $currency
     * @param string|null $note
     * 
     * @return self
     */
    public static function create(?string $id, string $userId, string $onDate, float $salary, string $currency, ?string $note): self
    {
        return new self(
            id: $id, 
            userId: $userId, 
            onDate: Date::fromString($onDate), 
            salary: Salary::fromValue($salary), 
            currency: Currency::fromString($currency), 
            note: $note
        );
    }

    /**
     * @param Request $request
     * @param int|null $id
     * 
     * @return self
     */
    public static function fromRequest(Request $request, ?int $id = null): self
    {
        return new self(
            id: $id,
            userId: $request->user_id,
            onDate: Date::fromString($request->on_date),
            salary: Salary::fromValue($request->salary),
            currency: Currency::fromString($request->currency),
            note: $request->note
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'on_date' => $this->onDate->format(),
            'salary' => $this->salary->toString(),
            'currency' => $this->currency->toString(),
            'note' => $this->note
        ];
    }
}