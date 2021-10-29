<?php

declare(strict_types=1);

namespace Acme\Basket;

use Acme\DeliveryRuleSet;
use Acme\NoApplicableOfferException;
use Acme\OfferSet;
use Acme\Product;
use Acme\ProductCatalogue;
use Acme\ProductNotFoundException;

class Basket
{
    private ProductCatalogue $catalogue;
    private DeliveryRuleSet $deliveryRuleSet;
    private OfferSet $offerSet;
    /**
     * @var array|Product[]
     */
    private array $products = [];

    /**
     * @param ProductCatalogue $catalogue
     * @param DeliveryRuleSet $deliveryRuleSet
     * @param OfferSet $offerSet
     */
    public function __construct(ProductCatalogue $catalogue, DeliveryRuleSet $deliveryRuleSet, OfferSet $offerSet)
    {
        $this->catalogue = $catalogue;
        $this->deliveryRuleSet = $deliveryRuleSet;
        $this->offerSet = $offerSet;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function add(string $productCode): void
    {
        $product = $this->catalogue->getByCode($productCode);

        $this->products[] = $product;
    }

    /**
     * @return array|Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function total(): float
    {
        return $this->calculateTotal() / 100;
    }

    /**
     * @return int
     */
    private function calculateProductsTotal(): int
    {
        try {
            $this->offerSet->calculateProductsTotal($this->getProducts());
        } catch (NoApplicableOfferException $exception) {
            return array_reduce($this->getProducts(), static function (int $carry, Product $product) {
                return $carry + $product->getPriceInCents();
            }, 0);
        }
    }

    private function calculateTotal(): int
    {
        $productsTotal = $this->calculateProductsTotal();

        return $productsTotal + $this->deliveryRuleSet->calculateDeliveryCost($productsTotal);
    }
}