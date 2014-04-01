desk-php
========

PHP wrapper for Desk.com's API. A work in progress. Currently, only the "Customer" endpoint is hooked up. Requires PHP 5.4+. Contributions appreciated.

Installation
------------

Not yet published to packagist since it is half-baked. You can use it by adding the repository to your composer.json file:

```js
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/bradleyboy/desk-php"
    }
],
"require": {
    "bradleyboy/desk-php": "dev-master"
}
```

Usage
-----

```php
use Desk\Desk;

// Desk uses basic auth. For example, myaccount.desk.com:
$desk = new Desk('myaccount', 'john@doe.com', 'password');

// Get all customers
$customers = $desk->customers->all();

// Search customers
$customers = $desk->customers->search(['email' => 'jack@doe.com']);

// Get a customer
// Desk uses HAL identifiers instead of numeric IDs.
$customer = $desk->customers->setIdentifier('/api/v2/customers/111111')->get();

// Create a customer
$customer = $desk->customers->create([
    'emails' => [
        [ 'type' => 'home', 'value' => 'jill@doe.com' ]
    ],
    'first_name' => 'Jill',
    'last_name' => 'Doe'
]);

// Update a customer
$customer = $desk->customers->setIdentifier('/api/v2/customers/111111')->update([
    'first_name' => 'Jane'
]);

// Get a customer's cases
$customer = $desk->customers->setIdentifier('/api/v2/customers/111111')->cases();
```
