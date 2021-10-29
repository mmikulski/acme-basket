<?php

declare(strict_types=1);

namespace Acme\Basket;

use Acme\DeliveryRuleSet;
use Acme\OfferSet;
use Acme\ProductCatalogue;

class Basket
{
    private ProductCatalogue $catalogue;
    private DeliveryRuleSet $deliveryRuleSet;
    private OfferSet $offerSet;

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
}