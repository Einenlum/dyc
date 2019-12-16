<?php

namespace Fixtures\Foo\Http;

use Fixtures\Foo\Repository\ProductRepository;

class Controller
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getPriceAction(int $productId)
    {
        return $this->productRepository->getProductPriceInEuro($productId);
    }
}
