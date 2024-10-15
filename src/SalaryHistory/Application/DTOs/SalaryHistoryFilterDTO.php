<?php 
namespace Src\SalaryHistory\Application\DTOs;

use Illuminate\Http\Request;
use Src\Common\Domain\ValueObjects\Date;
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
        $userId = $request->user_id;
        $dateRange = DateRange::fromString($request->from_date, $request->to_date);
        
        return new self(
            $userId,
            $dateRange
        );
    }
}