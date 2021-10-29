<?php

declare(strict_types=1);

namespace Acme\Basket;

use Acme\DeliveryRule;
use Acme\ProductCatalogue;
use Acme\ProductOffer;

class Basket
{
    private ProductCatalogue $catalogue;
    /**
     * @var array|DeliveryRule[]
     */
    private array $deliveryRules;
    /**
     * @var array|ProductOffer[]
     */
    private array $offers;

    /**
     * @param ProductCatalogue $catalogue
     * @param array $deliveryRules|DeliveryRule[]
     * @param array $offers|Offer[]
     */
    public function __construct(ProductCatalogue $catalogue, array $deliveryRules, array $offers)
    {
        $this->catalogue = $catalogue;
        $this->deliveryRules = $deliveryRules;
        $this->offers = $offers;
    }
}