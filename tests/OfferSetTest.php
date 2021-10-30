<?php

namespace Acme;

use PHPUnit\Framework\TestCase;

class OfferSetTest extends TestCase
{

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

    //TODO: testMultipleOffers
}
