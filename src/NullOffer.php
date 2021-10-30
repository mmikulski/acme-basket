<?php

declare(strict_types=1);

namespace Acme;


class NullOffer implements ProductOffer
{
    private string $productCode;

    /**
     * @param string $productCode
     */
    public function __construct(string $productCode)
    {
        $this->productCode = $productCode;
    }

    public function calculateProductsTotal(array $productAndAmount): int
    {
        return $productAndAmount[0]->getPriceInCents() * $productAndAmount[1];
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }
}