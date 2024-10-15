<?php
namespace Src\Common\Domain\ValueObjects;

use Src\Common\Domain\Exceptions\InvalidDateRangeException;
use Src\Common\Domain\ValueObjects\Date;

final class DateRange
{
    public function __construct(private Date $fromDate, private Date $toDate) 
    {
        if ($fromDate->isAfter($toDate)) {
            throw new InvalidDateRangeException();
        }
    }

    public static function fromString(?string $fromDate, ?string $toDate): ?self
    {
        if(!$fromDate || !$toDate) {
            return null;
        }

        return new static(Date::fromString($fromDate), Date::fromString($toDate));
    }

    public function getFromDate(): Date
    {
        return $this->fromDate;
    }

    public function getToDate(): Date
    {
        return $this->toDate;
    }
    
    public function toArray(string $format = 'Y-m-d H:i:s'): array 
    {
        return [
            $this->fromDate->format($format),
            $this->toDate->format($format)
        ];
    }
}