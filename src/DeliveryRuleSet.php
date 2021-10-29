<?php

declare(strict_types=1);

namespace Acme;

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

    public function calculateDeliveryCost(int $productsTotal): int
    {
        return $this->matchApplicableRule($productsTotal)->getPriceInCents();
    }

    private function matchApplicableRule(int $productsTotal): DeliveryRule
    {
        foreach ($this->rules as $rule) {
            assert($rule instanceof DeliveryRule);
            if ($productsTotal >= $rule->getLowerBoundary() && $productsTotal <= $rule->getUpperBoundary()) {
                return $rule;
            }
        }
        return new DeliveryRule(0, -1, -1);
    }
}