desk-php
========

[![Build Status](https://travis-ci.org/bradleyboy/desk-php.svg?branch=master)](https://travis-ci.org/bradleyboy/desk-php) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bradleyboy/desk-php/badges/quality-score.png?s=eadc9bb3010e34e2a665efb6334d55a2299404f5)](https://scrutinizer-ci.com/g/bradleyboy/desk-php/)

PHP wrapper for Desk.com's API. A work in progress. Currently, only the "Customers" endpoint is hooked up. Requires PHP 5.4+. Contributions appreciated.

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

// With basic auth. For example, myaccount.desk.com:
$desk = new Desk('myaccount', 'john@doe.com', 'password');


// With oauth. For example, myaccount.desk.com:
$desk = new Desk('myaccount', null, null, 'myconsumerkey', 'myconsumersecret', 'mytoken', 'mytokensecret');

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
