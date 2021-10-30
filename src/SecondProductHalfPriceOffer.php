<?php

declare(strict_types=1);

namespace Acme;

class SecondProductHalfPriceOffer implements ProductOffer
{
    private string $productCode;

    public function __construct(string $productCode)
    {
        $this->productCode = $productCode;
    }

    public function calculateProductsTotal(array $productAndAmount): int
    {
        $totalCost = 0;
        assert($productAndAmount[0] instanceof Product);
        if ($productAndAmount[0]->getCode() !== $this->productCode) {
            throw new IncorrectProductCodeException();
        }

        $totalCost += ($this->countFullPriceProducts($productAndAmount[1]) *
                $productAndAmount[0]->getPriceInCents()) +
            ($this->countHalfPriceProducts($productAndAmount[1]) *
                (int)round($productAndAmount[0]->getPriceInCents() / 2));

        return $totalCost;
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