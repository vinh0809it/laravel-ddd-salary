<?php

namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Http\Request;
use Src\Common\Domain\ValueObjects\Date;
use Src\SalaryHistory\Domain\ValueObjects\Currency;
use Src\SalaryHistory\Domain\ValueObjects\Salary;

final class UpdateSalaryHistoryDTO
{
    public function __construct(
        public string $id,
        public ?Date $onDate = null,
        public ?Salary $salary = null,
        public ?Currency $currency = null,
        public ?string $note = null
    ){}

    public static function create(
        string $id, 
        ?Date $onDate = null, 
        ?Salary $salary = null, 
        ?Currency $currency = null, 
        ?string $note = null
    ): self
    {
        return new self(
            id: $id, 
            onDate: $onDate, 
            salary: $salary, 
            currency: $currency, 
            note: $note
        );
    }

    /**
     * @param Request $request
     * @param int|null $id
     * 
     * @return self
     */
    public static function fromRequest(Request $request, int $id): self
    {
        $onDate = $request->on_date ? Date::fromString($request->on_date) : null;
        $salary = $request->salary ? Salary::fromValue($request->salary) : null;
        $currency = $request->currency ? Currency::fromString($request->currency) : null;

        return new self(
            id: $id,
            onDate: $onDate,
            salary: $salary,
            currency: $currency,
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
            'on_date' => $this->onDate->format(),
            'salary' => $this->salary->getAmount(),
            'currency' => $this->currency->toString(),
            'note' => $this->note
        ];
    }
}