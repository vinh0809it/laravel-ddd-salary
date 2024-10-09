<?php

namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SalaryHistoryDTO
{
    public function __construct(
        public ?string $id, 
        public string $userId, 
        public string $onDate, 
        public string $salary, 
        public ?string $note
    ){}

    /**
     * @param string|null $id
     * @param string $userId
     * @param string $onDate
     * @param string $salary
     * @param string|null $note
     * 
     * @return self
     */
    public static function create(?string $id, string $userId, string $onDate, string $salary, ?string $note): self
    {
        return new self(
            id: $id, 
            userId: $userId, 
            onDate: $onDate, 
            salary: $salary, 
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
            userId: $request->string('user_id'),
            onDate: $request->string('on_date'),
            salary: $request->string('salary'),
            note: $request->string('note')
        );
    }

    /**
     * @param mixed $entities SalaryHistory Entities
     * 
     * @return array
     */
    public static function toResponse(mixed $entities): array
    {
        if(!($entities instanceof Collection)) {
            $entities = [$entities];
        }

        $result = [];

        foreach($entities as $entity) {
            $result[] = [
                'id' => $entity->id,
                'user_id' => $entity->userId->toString(),
                'on_date' => $entity->onDate->toString(),
                'salary' => $entity->salary->toString(),
                'note' => $entity->note
            ];
        };

        return $result;
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
            'note' => $this->note
        ];
    }
}