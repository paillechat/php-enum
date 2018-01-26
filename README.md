# Enum

[![Build Status](https://travis-ci.org/paillechat/php-enum.svg?branch=master)](https://travis-ci.org/paillechat/php-enum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/paillechat/php-enum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/paillechat/php-enum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/paillechat/php-enum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/paillechat/php-enum/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/paillechat/php-enum/version.png)](https://packagist.org/packages/paillechat/php-enum)
[![Total Downloads](https://poser.pugx.org/paillechat/php-enum/downloads.png)](https://packagist.org/packages/paillechat/php-enum)

A PHP 7+ enumeration library.

## Why?

To create perfect enums for PHP library 

## Installation
```
composer require "paillechat/php-enum:^2.0"
```

## Usage

Declare enum class by extending basic `Enum` and filling it with constants. 
Constant value does not matter. You can fill it with any payload you can utilize as
general constant, but we suggest you to keep constants as `protected` as possible 

```php
<?php

use Paillechat\Enum\Enum;

/**
 * These docs are used only to help IDE
 * 
 * @method static static ONE
 * @method static static TWO
 */
class IssueType extends Enum 
{
    protected const ONE = 1;
    protected const TWO = 2;
} 

# Now you can create enum via named static call
/** @var Enum $one */
$one = IssueType::ONE();

# Enums keeps strict equality
$one1 = IssueType::ONE();
$one2 = IssueType::ONE();
$two = IssueType::TWO();

$one1 === $one2;
$one !== $two;

# Enums plays well with built-in functions
\in_array(IssueType::ONE(), [$one, $two], true);

# Enums plays well with signature type checks
function moveIssue(IssueType $type) {
    if ($type === IssueType::ONE()) {
        throw new \LogicException();
    }
    
    // ....
}

# You can convert enum to name and back
$name = $one->getName();
$new = IssueType::$name();
```
