<?php

declare(strict_types=1);

use Acme\Basket\Basket;
use Acme\DeliveryRule;
use Acme\ProductCatalogue;
use Acme\ProductOffer;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertInstanceOf;

class BasketTest extends TestCase
{
  final public function testBasketInitialisedWithCatalogueRulesAndOffers(): void
  {
      $catalogue = new ProductCatalogue();
      $deliveryRule = new DeliveryRule();
      $offer = new ProductOffer();

      $basket = new Basket($catalogue, [$deliveryRule], [$offer]);
      assertInstanceOf(Basket::class, $basket);
  }
}
