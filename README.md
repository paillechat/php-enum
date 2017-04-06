# Enum


[![Build Status](https://travis-ci.org/paillechat/php-enum.svg?branch=master)](https://travis-ci.org/paillechat/php-enum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/paillechat/php-enum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/paillechat/php-enum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/paillechat/php-enum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/paillechat/php-enum/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/paillechat/php-enum/version.png)](https://packagist.org/packages/paillechat/php-enum)
[![Total Downloads](https://poser.pugx.org/paillechat/php-enum/downloads.png)](https://packagist.org/packages/paillechat/php-enum)

A PHP 7+ enumeration library.

## Why?
SplEnum not supported in php 7.

## Installation
```
composer require paillechat/php-enum
```

## Declare
```php
class IssueType extends Enum 
{
    // if you want use default value define __default constant, like this
    const __default = 0;
    
    const ONE = 1;
    const TWO = 2;
}
```

## Usage
```php
// create issue type, with value 
$type = new IssueType(IssueType::ONE);
// or with default value
$type = new IssueType();

function proccessIssue(IssueType $type) 
{
    // ...
}

```
