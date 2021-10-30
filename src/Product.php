<?php

declare(strict_types=1);

namespace Acme;

use Money\Money;

class Product
{
    private string $code;
    private Money $price;

    /**
     * @param string $code
     * @param Money $price
     */
    public function __construct(string $code, Money $price)
    {
        $this->code = $code;
        $this->price = $price;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
}