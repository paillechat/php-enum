<?php

namespace Paillechat\Enum\Tests;

/**
 * @method static static THREE
 * @method static static FOUR
 */
final class DummyDivergedEnum extends DummyEnum
{
    protected const THREE = 3;
    protected const FOUR = 4;
}
