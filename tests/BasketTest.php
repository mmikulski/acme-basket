<?php

declare(strict_types=1);

use Acme\Basket\Basket;
use Acme\DeliveryRule;
use Acme\DeliveryRuleSet;
use Acme\OfferSet;
use Acme\Product;
use Acme\ProductCatalogue;
use Acme\ProductNotFoundException;
use Acme\SecondProductHalfPriceOffer;
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
      $product = new Product('R01', 32.95);
      $catalogue = new ProductCatalogue();
      $catalogue->addProdut($product);
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
      $product = new Product('R01', 32.95);
      $catalogue = new ProductCatalogue();
      $deliveryRuleSet = new DeliveryRuleSet();
      $offerSet = new OfferSet();

      $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);

      $this->expectException(ProductNotFoundException::class);
      $basket->add($product->getCode());
  }

  final public function testTotalCostReturnsSumOfProductPrices(): void
  {
      $product = new Product('R01', 32.95);
      $catalogue = new ProductCatalogue();
      $catalogue->addProdut($product);
      $deliveryRuleSet = new DeliveryRuleSet();
      $offerSet = new OfferSet();

      $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);
      $basket->add($product->getCode());
      $basket->add($product->getCode());

      self::assertEquals(($product->getPriceInCents() + $product->getPriceInCents()) / 100, $basket->total());
  }

    public function DeliveryCostDataProvider(): array
    {
        $deliveryRuleSet = new DeliveryRuleSet();
        $deliveryRuleSet->addRule(new DeliveryRule((int)(4.95 * 100), 0, 5000));
        $deliveryRuleSet->addRule(new DeliveryRule((int)(2.95 * 100), 5001, 9000));
        $deliveryRuleSet->addRule(new DeliveryRule(0, 9001, PHP_INT_MAX));
        return [
            [
                $deliveryRuleSet,
                new Product('XYZ', 49.99),
                (4999 + 495) / 100
            ],
            [
                $deliveryRuleSet,
                new Product('XYZ', 89.99),
                (8999 + 295) / 100
            ],
            [
                $deliveryRuleSet,
                new Product('XYZ', 99.99),
                (9999) / 100
            ]
        ];
    }

    /**
     * @dataProvider DeliveryCostDataProvider
     * @throws ProductNotFoundException
     */
    final public function testTotalCostIncludingDeliveryCost(
        DeliveryRuleSet $deliveryRuleSet,
        Product         $product,
        float           $expectedTotal
    ): void
    {
        $catalogue = new ProductCatalogue();
        $catalogue->addProdut($product);
        $offerSet = new OfferSet();

        $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);
        $basket->add($product->getCode());

        self::assertEquals($expectedTotal, $basket->total());
    }

    final public function testTotalCostWithAnOffer(): void
    {
        $product = new Product('R01', 32.95);
        $catalogue = new ProductCatalogue();
        $catalogue->addProdut($product);
        $deliveryRuleSet = new DeliveryRuleSet();
        $offerSet = new OfferSet();
        $offerSet->addOffer(new SecondProductHalfPriceOffer());

        $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);
        $basket->add($product->getCode());
        $basket->add($product->getCode());

        self::assertEquals(($product->getPriceInCents() + round($product->getPriceInCents() / 2)) / 100, $basket->total());
    }
}
