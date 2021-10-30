<?php

declare(strict_types=1);

namespace Acme;

use Money\Money;

class OfferSet
{
    /**
     * @var array|ProductOffer
     */
    private array $offers = [];

    public function addOffer(SecondProductHalfPriceOffer $offer): void
    {
        $this->offers[] = $offer;
    }

    /**
     * @param array $products|Product[]
     * @throws NoApplicableOfferException
     */
    public function calculateProductsTotal(array $products): Money
    {
        if (empty($this->offers)) {
            throw new NoApplicableOfferException();
        }

        $productsTotal = Money::USD(0);

        $productsAndAmounts = $this->productsAndAmountsByKey($products);

        foreach ($productsAndAmounts as $productCode => $productAndAmount) {
            $productOffer = $this->getOfferByProductKey($productCode);
            $productsTotal = $productsTotal->add($productOffer->calculateProductsTotal($productAndAmount));
        }

        return $productsTotal;
    }

    private function productsAndAmountsByKey(array $products): array
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

        return $productAmounts;
    }

    private function getOfferByProductKey(string $productCode): ProductOffer
    {
        foreach ($this->offers as $offer) {
            assert($offer instanceof ProductOffer);
            if ($offer->getProductCode() === $productCode) {
                return $offer;
            }
        }

        return new NullOffer($productCode);
    }
}