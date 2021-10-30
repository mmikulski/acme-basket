<?php

declare(strict_types=1);

namespace Acme;

use Money\Money;

class DeliveryRule
{
    private Money $price;
    private Money $lowerBoundary;
    private Money $upperBoundary;

    /**
     * @param Money $price
     * @param Money $lowerBoundary
     * @param Money $upperBoundary
     */
    public function __construct(Money $price, Money $lowerBoundary, Money $upperBoundary)
    {
        $this->price = $price;
        $this->lowerBoundary = $lowerBoundary;
        $this->upperBoundary = $upperBoundary;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getLowerBoundary(): Money
    {
        return $this->lowerBoundary;
    }

    public function getUpperBoundary(): Money
    {
        return $this->upperBoundary;
    }
}