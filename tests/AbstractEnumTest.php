<?php

namespace Paillechat\Enum\Tests;

use Paillechat\Enum\Enum;
use PHPUnit\Framework\TestCase;

class AbstractEnumTest extends TestCase
{
    public function testSuccess()
    {
        $enum = new DummyEnum(DummyEnum::ONE);

        $this->assertInstanceOf(Enum::class, $enum);
    }

    /**
     * @expectedException \Paillechat\Enum\Exception\EnumException
     * @expectedExceptionMessage Value bar not exist in enum Paillechat\Enum\Tests\DummyEnum
     */
    public function testUnrecognisedValue()
    {
        new DummyEnum('bar');
    }

    public function testDefaultValue()
    {
        $enum = new DummyWithDefaultEnum();

        $this->assertEquals('bar', $enum);
    }

    /**
     * @expectedException \Paillechat\Enum\Exception\EnumException
     * @expectedExceptionMessage No default value in Paillechat\Enum\Tests\DummyEnum enum
     */
    public function testWhenNoDefault()
    {
        new DummyEnum();
    }

    public function testToInt()
    {
        $enum = new DummyEnum(DummyEnum::ONE);
        $this->assertEquals(1, $enum->toInt());
    }

    /**
     * @expectedException  \Paillechat\Enum\Exception\EnumException
     * @expectedExceptionMessage Value no mismatch integer type
     */
    public function testCantBeInt()
    {
        $enum = new DummyWithDefaultEnum();
        $enum->toInt();
    }

    public function testMagicStaticConstructorCreateEnum()
    {
        $this->assertEquals(new DummyEnum(DummyEnum::ONE), DummyEnum::ONE());
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unknown static constructor "THREE" for Paillechat\Enum\Tests\DummyEnum
     */
    public function testMagicStaticConstructorThrowsBadMethodCallException()
    {
        DummyEnum::THREE();
    }

    /**
     * @dataProvider dataForGetListTest
     *
     * @param bool $includeDefault
     * @param array $expected
     */
    public function testGetConstList($includeDefault, $expected)
    {
        $this->assertEquals($expected, DummyWithDefaultEnum::getConstList($includeDefault));
    }

    public function dataForGetListTest()
    {
        return [
            [
                true,
                [
                    '__default' => 'bar',
                    'FOO' => 'foo',
                    'ONE' => 1,
                ]
            ],
            [
                false,
                [
                    'FOO' => 'foo',
                    'ONE' => 1,
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataForTestEquals
     *
     * @param Enum $first
     * @param Enum $second
     * @param bool $expected
     */
    public function testEquals($first, $second, $expected)
    {
        $result = $first->equals($second);
        $this->assertEquals($expected, $result);
    }

    public function dataForTestEquals()
    {
        return [
            [new DummyEnum(1), new DummyEnum(1), true],
            [new DummyEnum(1), new DummyEnum(2), false],
            [new DummyEnum(1), new DummyWithDefaultEnum(1), false],
        ];
    }

    public function testExtendingEnumKeepsBackwardEquality()
    {
        self::assertTrue(DummyEnum::ONE()->equals(DummyExtendingEnum::ONE()));
        self::assertTrue(DummyExtendingEnum::ONE()->equals(DummyEnum::ONE()));
    }

    public function testDivergedEnumsAreNotEqual()
    {
        self::assertFalse(DummyDivergedEnum::ONE()->equals(DummyExtendingEnum::ONE()));
        self::assertFalse(DummyExtendingEnum::ONE()->equals(DummyDivergedEnum::ONE()));
    }

    public function testExistenceOfTwoEnumClasses()
    {
        $constantsDummy = DummyEnum::getConstList();
        $constantsSecondDummy = SecondDummyEnum::getConstList();

        $this->assertEquals(['ONE' => 1, 'TWO' => 2], $constantsDummy);
        $this->assertEquals(['THREE' => 3, 'FOUR' => 4], $constantsSecondDummy);
    }
}
