<?php

namespace Fixtures\Foo\Currency;

interface RateConverter
{
    public function convertToEuro(int $amount, string $fromCurrency): int;
}
