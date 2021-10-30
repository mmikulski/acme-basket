<?php

declare(strict_types=1);

namespace Acme;

use Money\Money;

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

    public function calculateProductsTotal(array $productAndAmount): Money
    {
        return $productAndAmount[0]->getPrice()->multiply($productAndAmount[1]);
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }
}
