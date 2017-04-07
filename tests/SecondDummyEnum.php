<?php

namespace Paillechat\Enum\Tests;

use Paillechat\Enum\Enum;
use Paillechat\Enum\EnumValueToIntegerTrait;

class SecondDummyEnum extends Enum
{
    use EnumValueToIntegerTrait;

    const TREE = 3;
    const FOUR = 4;
}
