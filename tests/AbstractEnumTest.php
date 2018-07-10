<?php

namespace Paillechat\Enum\Tests;

use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\TestCase;

class AbstractEnumTest extends TestCase
{
    public function testSuccess(): void
    {
        $enum = DummyEnum::ONE();

        $this->assertInstanceOf(DummyEnum::class, $enum);

        self::assertSame('ONE', (string) $enum);
        self::assertSame('ONE', $enum->getName());
    }

    public function testEnumConstList(): void
    {
        $actual = DummyExtendingEnum::getConstList();
        asort($actual);

        $expected = [
            'ONE',
            'TWO',
            'THREE',
            'FOUR',
        ];
        sort($expected);
        sort($actual);
        self::assertSame($expected, $actual);
    }

    /**
     * @expectedException \Paillechat\Enum\Exception\EnumException
     * @expectedExceptionMessage Unknown member "THREE" for enum Paillechat\Enum\Tests\DummyEnum
     */
    public function testMagicStaticConstructorThrowsBadMethodCallException(): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        DummyEnum::THREE();
    }

    public function testStaticConstructorEnsuresStrictEquality(): void
    {
        $first = DummyEnum::ONE();
        $second = DummyEnum::ONE();

        self::assertSame($first, $second);
    }

    public function testInheritanceKeepsStrictEquality(): void
    {
        $first = DummyEnum::ONE();
        $second = DummyExtendingEnum::ONE();

        self::assertSame($first, $second);
    }

    public function testStaticConstructorAllowsInternalFunctions(): void
    {
        $haystack = [DummyEnum::ONE(), DummyExtendingEnum::TWO()];
        $needle = DummyExtendingEnum::TWO();

        self::assertContains($needle, $haystack);
    }

    public function testDivergenceBreaksEquality(): void
    {
        $first = DummyDivergedEnum::THREE();
        $second = DummyExtendingEnum::THREE();

        self::assertNotSame($first, $second);
        self::assertNotEquals($first, $second);
    }

    public function testCreateByName(): void
    {
        self::assertSame(DummyEnum::ONE(), DummyEnum::createByName('ONE'));
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Notice
     */
    public function testCreateByNameThrowsNoticeForNonCanonicalNames(): void
    {
        DummyEnum::createByName('One');
    }
}
