<?php

declare(strict_types=1);

namespace Acme;

class DeliveryRule
{

    private int $priceInCents;
    private int $lowerBoundary;
    private int $upperBoundary;

    /**
     * @param int $priceInCents
     * @param int $lowerBoundary
     * @param int $upperBoundary
     */
    public function __construct(int $priceInCents, int $lowerBoundary, int $upperBoundary)
    {
        $this->priceInCents = $priceInCents;
        $this->lowerBoundary = $lowerBoundary;
        $this->upperBoundary = $upperBoundary;
    }

    public function getPriceInCents(): int
    {
        return $this->priceInCents;
    }

    public function getLowerBoundary(): int
    {
        return $this->lowerBoundary;
    }

    public function getUpperBoundary(): int
    {
        return $this->upperBoundary;
    }
}