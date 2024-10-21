<?php

namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class StoreSalaryHistoryDTO
{
    public function __construct(
        public ?string $id = null, 
        public string $userId, 
        public string $onDate, 
        public float $salary, 
        public string $currency, 
        public ?string $note
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
    public static function fromRequest(Request $request, ?int $id = null): self
    {
        return new self(
            id: $id,
            userId: $request->user_id,
            onDate: $request->on_date,
            salary: $request->salary,
            currency: $request->currency,
            note: $request->note
        );
    }

    /**
     * @param mixed $entities SalaryHistory Entities
     * 
     * @return array
     */
    public static function toResponse(mixed $entities): array
    {
        $collection = $entities instanceof Collection ? $entities : collect([$entities]);
       
        return $collection->map(function ($entity) {
            return [
                'id' => $entity->getId(),
                'user_id' => $entity->getUserId(),
                'on_date' => $entity->getOnDate()->format(),
                'salary' => $entity->getSalary()->toString(),
                'currency' => $entity->getCurrency()->toString(),
                'note' => $entity->getNote(),
            ];
        })->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'on_date' => $this->onDate,
            'salary' => $this->salary,
            'currency' => $this->currency,
            'note' => $this->note
        ];
    }
}