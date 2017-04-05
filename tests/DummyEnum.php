<?php

namespace Paillechat\Enum\Tests;

use Paillechat\Enum\Enum;
use Paillechat\Enum\EnumValueToIntegerTrait;

class DummyEnum extends Enum
{
    use EnumValueToIntegerTrait;

    const ONE = 1;
    const TWO = 2;
}
