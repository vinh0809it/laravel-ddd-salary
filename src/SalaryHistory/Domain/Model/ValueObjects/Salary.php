<?php
namespace Src\SalaryHistory\Domain\Model\ValueObjects;

use InvalidArgumentException;
use Src\Common\Domain\Exceptions\RequiredException;

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
            throw new RequiredException('Salary cannot be null.');
        }

        if ($amount < 0) {
            throw new InvalidArgumentException('Salary cannot be negative.');
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

    public function jsonSerialize(): string
    {
        return $this->amount;
    }
}