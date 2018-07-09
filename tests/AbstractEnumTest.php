<?php

namespace Paillechat\Enum\Tests;

use Paillechat\Enum\Enum;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Paillechat\Enum\Enum
 * @covers \Paillechat\Enum\EnumValueToIntegerTrait
 */
final class AbstractEnumTest extends TestCase
{
    public function testCreateByNameConstructor()
    {
        self::assertEquals(DummyEnum::ONE(), DummyEnum::createByName('ONE'));
    }

    public function testEquality()
    {
        self::assertEquals(DummyEnum::ONE(), DummyEnum::ONE());
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

    public function dataForGetListTest(): array
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

    public function testExistenceOfTwoEnumClasses()
    {
        $constantsDummy = DummyEnum::getConstList();
        $constantsSecondDummy = SecondDummyEnum::getConstList();

        $this->assertEquals(['ONE' => 1, 'TWO' => 2], $constantsDummy);
        $this->assertEquals(['THREE' => 3, 'FOUR' => 4], $constantsSecondDummy);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unknown static constructor "THREE" for Paillechat\Enum\Tests\DummyEnum
     */
    public function testMagicStaticConstructorThrowsBadMethodCallException()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        DummyEnum::THREE();
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Notice
     */
    public function testCreateByNameConstructorThrowsNotice()
    {
        self::assertEquals(DummyEnum::ONE(), DummyEnum::createByName('One'));
    }

    /**
     * @group legacy
     * @expectedDeprecation %s. Use static constructors or createByName.
     */
    public function testSuccess()
    {
        $enum = new DummyEnum(DummyEnum::ONE);

        $this->assertInstanceOf(Enum::class, $enum);
    }

    /**
     * @group legacy
     * @expectedDeprecation %s. Use static constructors or createByName.
     *
     * @expectedException \Paillechat\Enum\Exception\EnumException
     * @expectedExceptionMessage Value bar not exist in enum Paillechat\Enum\Tests\DummyEnum
     */
    public function testUnrecognisedValue()
    {
        new DummyEnum('bar');
    }

    /**
     * @group legacy
     *
     * @expectedDeprecation %s. Use static constructors or createByName.
     * @expectedDeprecation %s. Define argument explicitly.
     * @expectedDeprecation %s. Cast to string instead.
     */
    public function testDefaultValue()
    {
        $enum = new DummyWithDefaultEnum();

        $this->assertEquals('bar', $enum);
    }

    /**
     * @group legacy
     *
     * @expectedDeprecation %s. Use static constructors or createByName.
     * @expectedDeprecation %s. Define argument explicitly.
     *
     * @expectedException \Paillechat\Enum\Exception\EnumException
     * @expectedExceptionMessage No default value in Paillechat\Enum\Tests\DummyEnum enum
     */
    public function testWhenNoDefault()
    {
        new DummyEnum();
    }

    /**
     * @group legacy
     * @expectedDeprecation %s. Use string-named enums.
     * @expectedDeprecation %s. Cast to string instead.
     *
     * @throws \Paillechat\Enum\Exception\EnumException
     */
    public function testToInt()
    {
        $enum = new DummyEnum(DummyEnum::ONE);
        $this->assertEquals(1, $enum->toInt());
    }

    /**
     * @group legacy
     * @expectedDeprecation %s. Define argument explicitly.
     * @expectedDeprecation %s. Use string-named enums.
     * @expectedDeprecation %s. Cast to string instead.
     *
     * @expectedException  \Paillechat\Enum\Exception\EnumException
     * @expectedExceptionMessage Value no mismatch integer type
     */
    public function testCantBeInt()
    {
        $enum = new DummyWithDefaultEnum();
        $enum->toInt();
    }

    /**
     * @group legacy
     * @expectedDeprecation %s. Use static constructors or createByName.
     */
    public function testMagicStaticConstructorCreateEnum()
    {
        $this->assertEquals(new DummyEnum(DummyEnum::ONE), DummyEnum::ONE());
    }

    /**
     * @dataProvider getLegacyExamplesForEquality
     *
     * @param Enum $first
     * @param Enum $second
     * @param bool $expected
     *
     * @group legacy
     *
     * @expectedDeprecation %s. Use weak comparison instead.
     * @expectedDeprecation %s. Cast to string instead.
     */
    public function testEquals($first, $second, $expected)
    {
        $result = $first->equals($second);
        $this->assertEquals($expected, $result);
    }

    public function getLegacyExamplesForEquality(): array
    {
        return [
            [@new DummyEnum(1), @new DummyEnum(1), true],
            [@new DummyEnum(1), @new DummyEnum(2), false],
            [@new DummyEnum(1), @new DummyWithDefaultEnum(1), false],
        ];
    }
}
