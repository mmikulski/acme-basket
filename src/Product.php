<?php

declare(strict_types=1);

namespace Acme;

class Product
{
    private string $code;
    private int $priceInCents;

    /**
     * @param string $code
     * @param float $priceInCents
     */
    public function __construct(string $code, float $priceInCents)
    {
        $this->code = $code;
        $this->priceInCents = (int)$priceInCents * 100;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrice(): float
    {
        return $this->priceInCents / 100;
    }

    public function getPriceInCents(): int
    {
        return $this->priceInCents;
    }
}