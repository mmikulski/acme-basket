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
            $productAmounts[$code] = [$product, isset($productAmounts[$code]) ?
                $productAmounts[$code] + 1 : 1];
        }


        $totalCost = 0;
        foreach ($products as $code => $productAndAmount) {
            assert($code instanceof Product);
            $totalCost += (($productAndAmount[1] / 2 + $productAndAmount[1] % 2) *
                    $productAndAmount[0]->getPriceInCents()) +
                ($productAndAmount[1] / 2 *
                    $productAndAmount[0]->getPriceInCents() / 2);
        }

        return $totalCost;
    }
}