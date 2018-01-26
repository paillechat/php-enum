<?php

namespace Paillechat\Enum\Tests;

use Paillechat\Enum\StaticEnum;
use PHPUnit\Framework\TestCase;

final class StaticEnumTest extends TestCase
{
    public function testStaticConstructorEnsuresStrictEquality()
    {
        $first = BaseStaticEnum::BASE_VAL_1();
        $second = BaseStaticEnum::BASE_VAL_1();

        self::assertSame($first, $second);
    }

    public function testInheritanceKeepsStrictEquality()
    {
        $first = BaseStaticEnum::BASE_VAL_1();
        $second = ExtendingEnum::BASE_VAL_1();

        self::assertSame($first, $second);
    }

    public function testStaticConstructorAllowsInternalFunctions()
    {
        $haystack = [BaseStaticEnum::BASE_VAL_1(), ExtendingEnum::BASE_VAL_2()];
        $needle = ExtendingEnum::BASE_VAL_1();

        self::assertContains($needle, $haystack);
    }

    public function testDivergenceBreaksEquality()
    {
        $first = DivergedStaticEnum::EXT_VAL_3();
        $second = ExtendingEnum::EXT_VAL_3();

        self::assertNotSame($first, $second);
        self::assertNotEquals($first, $second);
    }
}

/**
 * @method static static BASE_VAL_1
 * @method static static BASE_VAL_2
 */
class BaseStaticEnum extends StaticEnum
{
    protected const BASE_VAL_1 = 'val_1';
    protected const BASE_VAL_2 = 'val_2';
}

/**
 * @method static static EXT_VAL_3
 * @method static static EXT_VAL_4
 */
final class ExtendingEnum extends BaseStaticEnum
{
    protected const EXT_VAL_3 = 'val_3';
    protected const EXT_VAL_4 = 'val_4';
}

/**
 * @method static static EXT_VAL_3
 */
class DivergedStaticEnum extends BaseStaticEnum
{
    protected const EXT_VAL_3 = 'val_3';
}
