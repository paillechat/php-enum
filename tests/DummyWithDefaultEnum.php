<?php

namespace Paillechat\Enum\Tests;

use Paillechat\Enum\Enum;
use Paillechat\Enum\EnumValueToIntegerTrait;

class DummyWithDefaultEnum extends Enum
{
    use EnumValueToIntegerTrait;

    const __default = 'bar';

    const FOO = 'foo';
    const ONE = 1;
}
