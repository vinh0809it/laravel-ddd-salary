<?php
namespace Src\SalaryHistory\Domain\ValueObjects;

use Src\Common\Domain\Exceptions\InvalidSalaryException;
use Src\Common\Domain\Exceptions\ValueRequiredException;

final class Salary
{
    private float $amount;

    public function __construct(float $amount)
    {
        $this->validate($amount);
        $this->amount = $amount;
    }

    private function validate(float $amount): void
    {
        if($amount === null) {
            throw new ValueRequiredException('Salary cannot be null.');
        }

        if ($amount < 0) {
            throw new InvalidSalaryException('Salary cannot be negative.');
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function toString(): string
    {
        return number_format($this->amount, 2); 
    }

    public function add(Salary $other): Salary
    {
        return new self($this->amount + $other->getAmount());
    }

    public function subtract(Salary $other): Salary
    {
        $result = $this->amount - $other->getAmount();
        return new self($result); 
    }

    public function isEqual(Salary $other): bool
    {
        return $this->amount === $other->getAmount();
    }
}