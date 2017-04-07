<?php

namespace Paillechat\Enum;

use Paillechat\Enum\Exception\EnumException;

abstract class Enum
{
    /** @var mixed */
    protected $value = null;

    /** @var string */
    private static $defaultConstantName = '__default';

    /** @var \ReflectionClass[] */
    private static $reflections;

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        if ($value === null) {
            $value = static::getDefaultValue();
        }

        $this->assertValue($value);

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    final public function getValue()
    {
        return $this->value;
    }

    /**
     * Compares one Enum with another.
     *
     * @param Enum $enum
     *
     * @return bool True if Enums are equal, false if not equal
     */
    final public function equals(Enum $enum)
    {
        return $this->getValue() === $enum->getValue() && static::class == get_class($enum);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }

    /**
     * @param bool $includeDefault
     *
     * @return array
     */
    final public static function getConstList(bool $includeDefault = false): array
    {
        self::$reflections[static::class] =
            self::$reflections[static::class] ??
            new \ReflectionClass(static::class);

        return array_filter(
            self::$reflections[static::class]->getConstants(),
            function ($key) use ($includeDefault) {
                if ($includeDefault === false && $key === self::$defaultConstantName) {
                    return false;
                }

                return true;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @return mixed
     *
     * @throws EnumException
     */
    protected static function getDefaultValue()
    {
        $const = static::getConstList(true);

        if (!array_key_exists(self::$defaultConstantName, $const)) {
            throw EnumException::becauseNoDefaultValue(static::class);
        }

        return $const[self::$defaultConstantName];
    }

    /**
     * @param mixed $value
     *
     * @throws EnumException
     */
    private function assertValue($value)
    {
        $const = static::getConstList(true);

        $defaultValueUsed = $value === null && isset($const[self::$defaultConstantName]);

        if (!$defaultValueUsed && !in_array($value, $const, true)) {
            throw EnumException::becauseUnrecognisedValue(static::class, $value);
        }
    }
}
