<?php

declare(strict_types=1);

namespace Acme;

class ProductCatalogue
{
    /**
     * @var array | Product[]
     */
    private array $products = [];

    /**
     * @return array|Product[]
     */
    private function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getByCode(string $productCode): Product
    {
        if (!isset($this->getProducts()[$productCode])) {
            throw new ProductNotFoundException();
        }
        return $this->getProducts()[$productCode];
    }

    public function add(Product $product): void
    {
        $this->products[$product->getCode()] = $product;
    }
}