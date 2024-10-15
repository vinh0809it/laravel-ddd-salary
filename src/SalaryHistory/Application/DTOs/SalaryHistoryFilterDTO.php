<?php 
namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Http\Request;
use Src\Common\Domain\ValueObjects\DateRange;

class SalaryHistoryFilterDTO
{
    public function __construct(
        public readonly ?string $userId = null,
        public readonly ?DateRange $dateRange = null,
    ) 
    {}

    public static function fromRequest(Request $request): self
    {
        $userId = $request->input('user_id');
        $dateRange = DateRange::from($request->input('from_date'), $request->input('to_date'));

        return new self(
            $userId,
            $dateRange
        );
    }
}