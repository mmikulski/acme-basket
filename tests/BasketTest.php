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
use Money\Money;
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
      $product = new Product('R01', Money::USD(3295));
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
      $product = new Product('R01', Money::USD(3295));
      $catalogue = new ProductCatalogue();
      $deliveryRuleSet = new DeliveryRuleSet();
      $offerSet = new OfferSet();

      $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);

      $this->expectException(ProductNotFoundException::class);
      $basket->add($product->getCode());
  }

  final public function testTotalCostReturnsSumOfProductPrices(): void
  {
      $product = new Product('R01', Money::USD(3295));
      $catalogue = new ProductCatalogue();
      $catalogue->addProdut($product);
      $deliveryRuleSet = new DeliveryRuleSet();
      $offerSet = new OfferSet();

      $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);
      $basket->add($product->getCode());
      $basket->add($product->getCode());

      self::assertEquals($product->getPrice()->multiply(2), $basket->total());
  }

    public function DeliveryCostDataProvider(): array
    {
        $deliveryRuleSet = new DeliveryRuleSet();
        $deliveryRuleSet->addRule(new DeliveryRule(Money::USD(495), Money::USD(0), Money::USD(5000)));
        $deliveryRuleSet->addRule(new DeliveryRule(Money::USD(295), Money::USD(5001), Money::USD(9000)));
        $deliveryRuleSet->addRule(new DeliveryRule(Money::USD(0), Money::USD(9001), Money::USD(PHP_INT_MAX)));
        return [
            [
                $deliveryRuleSet,
                new Product('XYZ', Money::USD(4999)),
                Money::USD(4999)->add(Money::USD(495))
            ],
            [
                $deliveryRuleSet,
                new Product('XYZ', Money::USD(8999)),
                Money::USD(8999)->add(Money::USD(295))
            ],
            [
                $deliveryRuleSet,
                new Product('XYZ', Money::USD(9999)),
                Money::USD(9999)
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
        Money           $expectedTotal
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
        $product = new Product('R01', Money::USD(3295));
        $catalogue = new ProductCatalogue();
        $catalogue->addProdut($product);
        $deliveryRuleSet = new DeliveryRuleSet();
        $offerSet = new OfferSet();
        $offerSet->addOffer(new SecondProductHalfPriceOffer($product->getCode()));

        $basket = new Basket($catalogue, $deliveryRuleSet, $offerSet);
        $basket->add($product->getCode());
        $basket->add($product->getCode());

        self::assertEquals($product->getPrice()->add($product->getPrice()->divide(2)), $basket->total());
    }

    /**
     * @dataProvider expectedTotalsTestDataProvider
     * @throws ProductNotFoundException
     */
    final public function testTotalCostWithDeliveryAndOffer(Basket $basket, array $productCodes, Money $expectedTotal):
    void
    {
        foreach ($productCodes as $productCode) {
            $basket->add($productCode);
        }

        self::assertEquals($expectedTotal, $basket->total());
    }

    public function expectedTotalsTestDataProvider(): array
    {
        $redWidget = new Product('R01', Money::USD(3295));
        $greenWidget = new Product('G01', Money::USD(2495));
        $blueWidget = new Product('B01', Money::USD(795));
        $catalogue = new ProductCatalogue();
        $catalogue->addProdut($redWidget);
        $catalogue->addProdut($greenWidget);
        $catalogue->addProdut($blueWidget);
        $deliveryRuleSet = new DeliveryRuleSet();
        $deliveryRuleSet->addRule(new DeliveryRule(Money::USD(495), Money::USD(0), Money::USD(5000)));
        $deliveryRuleSet->addRule(new DeliveryRule(Money::USD(295), Money::USD(5001), Money::USD(9000)));
        $deliveryRuleSet->addRule(new DeliveryRule(Money::USD(0), Money::USD(9001), Money::USD(PHP_INT_MAX)));

        $offerSet = new OfferSet();
        $offerSet->addOffer(new SecondProductHalfPriceOffer($redWidget->getCode()));

        return [
            [
                new Basket($catalogue, $deliveryRuleSet, $offerSet),
                ['B01', 'G01'],
                Money::USD(3785)
            ],
            [
                new Basket($catalogue, $deliveryRuleSet, $offerSet),
                ['R01', 'R01'],
                Money::USD(5437)
            ],
            [
                new Basket($catalogue, $deliveryRuleSet, $offerSet),
                ['R01', 'G01'],
                Money::USD(6085)
            ],
            [
                new Basket($catalogue, $deliveryRuleSet, $offerSet),
                ['B01', 'B01', 'R01', 'R01', 'R01'],
                Money::USD(9827)
            ]
        ];
    }
}
