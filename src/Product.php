<?php

declare(strict_types=1);

namespace Acme;

class Product
{
    private string $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}