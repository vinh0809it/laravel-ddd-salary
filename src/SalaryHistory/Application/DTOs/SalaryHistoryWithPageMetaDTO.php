<?php

namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Support\Collection;
use Src\SalaryHistory\Domain\Entities\SalaryHistory;

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
                'id' => $entity->getId(),
                'user_id' => $entity->getUserId(),
                'on_date' => $entity->getOnDate()->format(),
                'salary' => $entity->getSalary()->toString(),
                'currency' => $entity->getCurrency()->toString(),
                'note' => $entity->getNote(),
            ];
        })->toArray();

        return [
            'data' => $responseData,
            'pagination' => $this->pagination,
            'sorting' => $this->sorting
        ];
    }
}