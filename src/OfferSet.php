<?php

declare(strict_types=1);

namespace Acme;

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
    public function calculateProductsTotal(array $products): int
    {
        if (empty($this->offers)) {
            throw new NoApplicableOfferException();
        }
        $offer = $this->offers[0];
        assert($offer instanceof ProductOffer);
        return $offer->calculateProductsTotal($products);
    }
}