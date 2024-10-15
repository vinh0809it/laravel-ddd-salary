<?php

namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Support\Collection;
use Src\SalaryHistory\Domain\Model\SalaryHistory;

class SalaryHistoryWithPageMetaDTO
{
    public function __construct(
        public Collection $salaryHistories, 
        public array $pagination, 
        public array $sorting
    ){}

    /**
     * @param Collection $data
     * @param array $pagination
     * @param array $sorting
     * 
     * @return self
     */
    public static function fromPaginatedEloquent(Collection $data, array $pagination, array $sorting): self
    {
        return new static($data, $pagination, $sorting);
    }

    /**
     * @return array
     */
    public function toResponse(): array
    {
        $responseData = $this->salaryHistories->map(function (SalaryHistory $entity) {
            return [
                'id' => $entity->id,
                'user_id' => $entity->userId,
                'on_date' => $entity->onDate->format(),
                'salary' => $entity->salary->toString(),
                'note' => $entity->note,
            ];
        })->toArray();

        return [
            'data' => $responseData,
            'pagination' => $this->pagination,
            'sorting' => $this->sorting
        ];
    }
}