<?php

namespace Fixtures\Foo\Currency\RateConverter;

use Fixtures\Foo\Currency\RateConverter;

class Fixer implements RateConverter
{
    public function convertToEuro(int $amount, string $fromCurrency): int
    {
        return $amount * 2;
    }
}
