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

    public function calculateProductsTotal(array $products): int
    {
        $productAmounts = [];

        foreach ($products as $product) {
            $code = $product->getCode();
            if (isset($productAmounts[$code])) {
                $productAmounts[$code] = [$product, $productAmounts[$code][1] + 1];
            } else {
                $productAmounts[$code] = [$product, 1];
            }
        }

        $totalCost = 0;
        foreach ($productAmounts as $productCode => $productAndAmount) {
            assert($productAndAmount[0] instanceof Product);
            if ($productCode === $this->productCode) {
                $totalCost += ($this->countFullPriceProducts($productAndAmount[1]) *
                        $productAndAmount[0]->getPriceInCents()) +
                    ($this->countHalfPriceProducts($productAndAmount[1]) *
                        (int)round($productAndAmount[0]->getPriceInCents() / 2));
            } else {
                $totalCost += $productAndAmount[1] *
                    $productAndAmount[0]->getPriceInCents();
            }
        }

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
}