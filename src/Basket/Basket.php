<?php

declare(strict_types=1);

namespace Acme\Basket;

use Acme\DeliveryRuleSet;
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
    private array $products;

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
}