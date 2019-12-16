<?php

namespace Fixtures\Foo\Repository;

use Fixtures\Foo\Currency\RateConverter;

class ProductRepository
{
    /**
     * @var RateConverter
     */
    private $rateConverter;

    public function __construct(RateConverter $rateConverter)
    {
        $this->rateConverter = $rateConverter;
    }

    public function getProductPriceInEuro(int $productId): int
    {
        return $this->rateConverter->convertToEuro($this->getProduct($productId)->price, 'USD');
    }

    private function getProduct(int $productId): \StdClass
    {
        $product = new \StdClass();
        $product->price = 1322;

        return $product;
    }
}
