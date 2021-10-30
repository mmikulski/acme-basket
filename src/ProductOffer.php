<?php

declare(strict_types=1);

namespace Acme;

use Money\Money;

interface ProductOffer
{
    public function calculateProductsTotal(array $productAndAmount): Money;
    public function getProductCode(): string;
}