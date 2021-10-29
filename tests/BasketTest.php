<?php

declare(strict_types=1);

use Acme\Basket\Basket;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertInstanceOf;

class BasketTest extends TestCase
{
  final public function testClassInitialised(): void
  {
    $basket = new Basket();
    assertInstanceOf(Basket::class, $basket);
  }
}
