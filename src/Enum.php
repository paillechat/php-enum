<?php

namespace Paillechat\Enum;

use Paillechat\Enum\Exception\EnumException;

abstract class Enum
{
    /** @var string */
    private static $defaultConstantName = '__default';
    /** @var \ReflectionClass[] */
    private static $reflections = [];
    /** @var mixed */
    protected $value;

    /**
     * @param mixed $value
     *
     * @throws EnumException
     */
    final public function __construct($value = null)
    {
        if ($value === null) {
            $value = static::getDefaultValue();
        }

        $this->assertValue($value);

        $this->value = $value;
    }

    /**
     * @param bool $includeDefault
     *
     * @return array
     */
    final public static function getConstList(bool $includeDefault = false): array
    {
        return array_filter(
            self::getEnumReflection(static::class)->getConstants(),
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
     * Creates enum instance with short static constructor
     *
     * @param string $name
     * @param array $arguments
     *
     * @return static
     *
     * @throws \BadMethodCallException
     */
    final public static function __callStatic(string $name, array $arguments)
    {
        $const = static::getConstList();

        if (!array_key_exists($name, $const)) {
            throw new \BadMethodCallException(sprintf('Unknown static constructor "%s" for %s', $name, static::class));
        }

        return static::createNamedInstance($name, $const[$name]);
    }

    protected static function getEnumReflection(string $class): \ReflectionClass
    {
        if (!array_key_exists($class, self::$reflections)) {
            self::$reflections[$class] = new \ReflectionClass($class);
        }

        return self::$reflections[$class];
    }

    /**
     * Create named enum instance
     *
     * @param string $name
     * @param mixed $value
     *
     * @return static
     *
     * @throws EnumException
     */
    protected static function createNamedInstance(string $name, $value)
    {
        return new static($value);
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
        $enumClass = get_class($enum);

        $thisClass = static::class;

        return $this->getValue() === $enum->getValue()
            && ($this instanceof $enumClass || $enum instanceof $thisClass);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
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
