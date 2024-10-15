<?php
namespace Src\SalaryHistory\Domain\ValueObjects;

use Src\SalaryHistory\Domain\Exceptions\UnsupportedCurrencyException;

final class Currency
{
    private string $currencyCode;
    private const SUPPORTED_CURRENCIES = ['USD', 'VND', 'JPY'];

    public function __construct(string $currencyCode)
    {
        if (!in_array($currencyCode, self::SUPPORTED_CURRENCIES)) {
            throw new UnsupportedCurrencyException('Unsupported currency code: '.$currencyCode);
        }

        $this->currencyCode = $currencyCode;
    }

    public static function fromString(string $currencyCode): self
    {
        return new static($currencyCode);
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function toString(): string
    {
        return $this->currencyCode;
    }

    public function equals(Currency $other): bool
    {
        return $this->currencyCode === $other->getCurrencyCode();
    }
}