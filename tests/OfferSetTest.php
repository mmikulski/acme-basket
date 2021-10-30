<?php

namespace Acme;

use PHPUnit\Framework\TestCase;

class OfferSetTest extends TestCase
{
    /**
     * @throws NoApplicableOfferException
     */
    public function testOfferAppliesToSpecificProductOnly(): void
    {
        $offeredProductCode = 'R01';
        $product = new Product($offeredProductCode, 32.95);
        $productB = new Product('G01', 24.95);

        $offerSet = new OfferSet();
        $offerSet->addOffer(new SecondProductHalfPriceOffer($offeredProductCode));

        $total = $offerSet->calculateProductsTotal([$product, $product, $productB, $productB]);

        self::assertEquals((32.95 + (round(32.95 * 100 / 2) / 100) + 24.95 + 24.95), $total / 100);
    }

    /**
     * @throws NoApplicableOfferException
     */
    public function testMultipleOffers(): void
    {
        $offeredProductCode = 'R01';
        $product = new Product($offeredProductCode, 32.95);
        $productB = new Product('G01', 24.95);

        $offerSet = new OfferSet();
        $offerSet->addOffer(new SecondProductHalfPriceOffer($product->getCode()));
        $offerSet->addOffer(new SecondProductHalfPriceOffer($productB->getCode()));

        $total = $offerSet->calculateProductsTotal([$product, $product, $productB, $productB]);

        self::assertEquals((32.95 + (round(32.95 * 100 / 2) / 100) + 24.95 + (round(24.95 * 100 / 2) / 100)), $total / 100);
    }
}
