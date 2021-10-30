<?php

declare(strict_types=1);

namespace Acme;

class SecondProductHalfPriceOffer implements ProductOffer
{

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
        foreach ($productAmounts as $productAndAmount) {
            assert($productAndAmount[0] instanceof Product);
            $totalCost += ($this->countFullPriceProducts($productAndAmount[1]) *
                    $productAndAmount[0]->getPriceInCents()) +
                ($this->countHalfPriceProducts($productAndAmount[1]) *
                    (int)round($productAndAmount[0]->getPriceInCents() / 2));
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