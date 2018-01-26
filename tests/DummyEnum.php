<?php

namespace Paillechat\Enum\Tests;

use Paillechat\Enum\Enum;

/**
 * @method static static ONE
 * @method static static TWO
 */
class DummyEnum extends Enum
{
    protected const ONE = 1;
    protected const TWO = 2;
}
