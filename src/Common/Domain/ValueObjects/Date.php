<?php

namespace Src\Common\Domain\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

class Date
{
    private DateTimeImmutable $date;

    public function __construct(string $dateString)
    {
        $this->date = $this->createDate($dateString);
    }

    private function createDate(string $dateString): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $dateString);

        if ($date === false) {
            throw new InvalidArgumentException('Invalid date format, expected Y-m-d.');
        }

        return $date;
    }

    public function toString(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function isEqual(Date $other): bool
    {
        return $this->date == $other->date;
    }
}