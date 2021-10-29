<?php

declare(strict_types=1);

use Acme\Basket\Basket;
use Acme\DeliveryRuleSet;
use Acme\OfferSet;
use Acme\Product;
use Acme\ProductCatalogue;
use Acme\ProductNotFoundException;
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

    /**
     * @throws ProductNotFoundException
     */
    final public function testAddMethodAcceptsProductCode(): void
  {
      $product = new Product('R01');
      $catalogue = new ProductCatalogue();
      $catalogue->add($product);
      $deliveryRuleSet = new DeliveryRuleSet();
      $offerSet = new OfferSet();

      $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);

      $basket->add($product->getCode());
      self::assertContains($product, $basket->getProducts());
  }

    /**
     * @throws ProductNotFoundException
     */
    final public function testAddMethodThrowsOnIncorrectCode(): void
  {
      $product = new Product('R01');
      $catalogue = new ProductCatalogue();
      $deliveryRuleSet = new DeliveryRuleSet();
      $offerSet = new OfferSet();

      $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);

      $this->expectException(ProductNotFoundException::class);
      $basket->add($product->getCode());
  }
}
