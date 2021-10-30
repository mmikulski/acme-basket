<?php

declare(strict_types=1);

namespace Acme;

use Money\Money;

class SecondProductHalfPriceOffer implements ProductOffer
{
    private string $productCode;

    public function __construct(string $productCode)
    {
        $this->productCode = $productCode;
    }

    public function calculateProductsTotal(array $productAndAmount): Money
    {
        $totalCost = Money::USD(0);
        assert($productAndAmount[0] instanceof Product);
        if ($productAndAmount[0]->getCode() !== $this->productCode) {
            throw new IncorrectProductCodeException();
        }

        return $totalCost->add(
            $productAndAmount[0]->getPrice()->multiply($this->countFullPriceProducts($productAndAmount[1])))->add($productAndAmount[0]->getPrice()->divide(2)->multiply($this->countHalfPriceProducts($productAndAmount[1]))
        );
    }

    /**
     * @param int $productAmount
     * @return int
     */
    private function countFullPriceProducts(int $productAmount): int
    {
        return (intdiv($productAmount, 2) + $productAmount % 2);
    }

    /**
     * @param int $productAmount
     * @return int
     */
    private function countHalfPriceProducts(int $productAmount): int
    {
        return intdiv($productAmount, 2);
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }
}