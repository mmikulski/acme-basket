# Acme Basket

## Prerequisites
- Docker

## Runtime
Repository assumes that Docker is installed.
Convenience scripts are provided for Linux (./scripts/*.sh) and Windows (./scripts/*.ps1).

## Setup
Install dependencies by running:
```shell
./scripts/composer.sh install
```

## Tests
Run tests:
```shell
./scripts/test.sh
```

## Business logic assumptions
- Delivery fee is calculated based on the total cost, after offers are applied. Current implementation of 
  DeliveryRuleSet has at least one issue - it doesn't validate overlap between different price ranges. So if the 
  specific rules will come with overlapping ranges first rule that applies will be used.
- Initially I have assumed that the offer applies to all products, ie get one of a type and second one half-price 
  and only the incorrect test results forced me to revisit the assumptions and notice that it should only apply to 
  specific product.
- The OfferSet object is the one that seems to be least polished. It should handle properly one offer per product 
  use case, but would have to be extended if other use cases would be needed, like offer codes or others.
- Monetary values should be held in dedicated object - see https://github.com/moneyphp/money, I have decided 
  to go with manual calculations to see how much effort and issues it will cause. Even in this fairly simple example 
  that caused unnecessary trouble.
- Currency rounding is then on each operation separately, using default rounding strategy which is called 'away from 
  zero' and is considered a commercial rounding applicable to currency. That lead to test results different from 
  expected in 2 cases.
