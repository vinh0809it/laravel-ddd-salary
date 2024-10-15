<?php
namespace Src\Common\Domain\ValueObjects;

use Src\Common\Domain\Exceptions\InvalidDateRangeException;
use Src\Common\Domain\ValueObjects\Date;
use Src\Common\Domain\Exceptions\MustBeProvidedTogetherException;

final class DateRange
{
    public function __construct(private Date $fromDate, private Date $toDate) 
    {
        if(!$fromDate || !$toDate) {
            throw new MustBeProvidedTogetherException();
        }

        if ($fromDate->isAfter($toDate)) {
            throw new InvalidDateRangeException();
        }
    }

    public static function from(?string $fromDate, ?string $toDate): ?self
    {
        if(!$fromDate && !$toDate) {
            return null;
        }
        
        $fromDate = new Date($fromDate);
        $toDate = new Date($toDate);

        return new static($fromDate, $toDate);
    }

    public function toArray(string $format = 'Y-m-d H:i:s'): array 
    {
        return [
            $this->fromDate->format($format),
            $this->toDate->format($format)
        ];
    }
}