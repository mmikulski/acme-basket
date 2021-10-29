<?php

declare(strict_types=1);

use Acme\Basket\Basket;
use Acme\DeliveryRuleSet;
use Acme\OfferSet;
use Acme\ProductCatalogue;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertInstanceOf;

class BasketTest extends TestCase
{
  final public function testBasketInitialisedWithCatalogueRulesAndOffers(): void
  {
      $catalogue = new ProductCatalogue();
      $deliveryRuleSet = new DeliveryRuleSet();
      $offerSet = new OfferSet();

      $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);
      assertInstanceOf(Basket::class, $basket);
  }
}
