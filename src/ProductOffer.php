<?php

declare(strict_types=1);

namespace Acme;

interface ProductOffer
{
    public function calculateProductsTotal(array $products): int;
}