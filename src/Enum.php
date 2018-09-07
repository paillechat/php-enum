<?php

namespace Paillechat\Enum;

use Paillechat\Enum\Exception\EnumException;

abstract class Enum
{
    /** @var string */
    private static $defaultConstantName = '__default';
    /** @var \ReflectionClass[] */
    private static $reflections;
    /** @var mixed */
    protected $value;

    /**
     * @param mixed $value
     * @param bool $deprecate
     *
     * @throws EnumException
     *
     * @deprecated use by-name constructor instead
     * @see Enum::createByName
     * @see Enum::__callStatic
     */
    final public function __construct($value = null, bool $deprecate = true)
    {
        if ($deprecate) {
            trigger_error(
                __METHOD__ . ' is deprecated and will be private in 2.0. ' .
                'Use static constructors or createByName.',
                E_USER_DEPRECATED
            );
        }

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
        try {
            self::$reflections[static::class] =
                self::$reflections[static::class] ??
                new \ReflectionClass(static::class);
            // @codeCoverageIgnoreStart
        } catch (\ReflectionException $e) {
            throw new \LogicException('Reflection exception for static::class is not expected.');
            // @codeCoverageIgnoreEnd
        }

        return array_filter(
            self::$reflections[static::class]->getConstants(),
            function ($key) use ($includeDefault) {
                return !($includeDefault === false && $key === self::$defaultConstantName);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Creates enum instance by name
     *
     * @param string $name
     *
     * @return static
     *
     * @throws \BadMethodCallException
     */
    final public static function createByName(string $name)
    {
        $canonicalName = strtoupper($name);
        if ($canonicalName !== $name) {
            $name = $canonicalName;
            trigger_error('PSR-1 requires constant to be declared in upper case.', E_USER_NOTICE);
        }

        $const = static::getConstList();

        if (!array_key_exists($name, $const)) {
            throw new \BadMethodCallException(sprintf('Unknown static constructor "%s" for %s.', $name, static::class));
        }

        try {
            return new static($const[$name], false);
            // @codeCoverageIgnoreStart
        } catch (EnumException $e) {
            throw new \LogicException('Existence of constant value is checked. Fix constructor.');
            // @codeCoverageIgnoreEnd
        }
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
        return static::createByName($name);
    }

    /**
     * @return mixed
     *
     * @throws EnumException
     *
     * @deprecated
     */
    protected static function getDefaultValue()
    {
        trigger_error('Default enum value is deprecated. Define argument explicitly.', E_USER_DEPRECATED);

        $const = static::getConstList(true);

        if (!array_key_exists(self::$defaultConstantName, $const)) {
            throw EnumException::becauseNoDefaultValue(static::class);
        }

        return $const[self::$defaultConstantName];
    }

    /**
     * @return mixed
     *
     * @deprecated Cast to string instead
     */
    final public function getValue()
    {
        trigger_error(
            __METHOD__ . ' is deprecated and will be removed in 2.0. ' .
            'Cast to string instead.',
            E_USER_DEPRECATED
        );

        return $this->value;
    }

    /**
     * Compares one Enum with another.
     *
     * @param Enum $enum
     *
     * @return bool True if Enums are equal, false if not equal
     *
     * @deprecated Use weak comparison instead
     */
    final public function equals(Enum $enum): bool
    {
        trigger_error(
            __METHOD__ . ' is deprecated and will be removed in 2.0. ' .
            'Use weak comparison instead.',
            E_USER_DEPRECATED
        );

        return (string) $this === (string) $enum && static::class === \get_class($enum);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->value;
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

        if (!$defaultValueUsed && !\in_array($value, $const, true)) {
            throw EnumException::becauseUnrecognisedValue(static::class, $value);
        }
    }
}
