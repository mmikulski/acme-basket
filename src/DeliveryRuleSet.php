<?php

declare(strict_types=1);

namespace Acme;

use Money\Money;

class DeliveryRuleSet
{

    /**
     * @var array|DeliveryRule[]
     */
    private array $rules = [];

    public function addRule(DeliveryRule $deliveryRule): void
    {
        $this->rules[] = $deliveryRule;
    }

    public function calculateDeliveryCost(Money $productsTotal): Money
    {
        return $this->matchApplicableRule($productsTotal)->getPrice();
    }

    private function matchApplicableRule(Money $productsTotal): DeliveryRule
    {
        foreach ($this->rules as $rule) {
            assert($rule instanceof DeliveryRule);
            if ($productsTotal->greaterThanOrEqual($rule->getLowerBoundary()) && $productsTotal->lessThanOrEqual(
                    $rule->getUpperBoundary())) {
                return $rule;
            }
        }
        return new DeliveryRule(Money::USD(0), Money::USD(-1), Money::USD(-1));
    }
}