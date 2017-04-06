<?php

namespace Paillechat\Enum;

use Paillechat\Enum\Exception\EnumException;

abstract class Enum
{
    /** @var string */
    private $defaultConstantName = '__default';

    /**  @var \ReflectionClass */
    private $reflection;

    /** @var mixed */
    protected $value = null;

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        if ($value === null) {
            $value = $this->getDefaultValue();
        }

        $this->assertValue($value);

        $this->value = $value;
    }

    /**
     * @param bool $includeDefault
     *
     * @return array
     */
    final public function getConstList(bool $includeDefault = false): array
    {
        $this->reflection = $this->reflection ?? new \ReflectionClass($this);

        return array_filter(
            $this->reflection->getConstants(),
            function ($key) use ($includeDefault) {
                if ($includeDefault === false && $key === $this->defaultConstantName) {
                    return false;
                }

                return true;
            },
            ARRAY_FILTER_USE_KEY
        );
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
        return $this->getValue() === $enum->getValue() && get_called_class() == get_class($enum);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     *
     * @throws EnumException
     */
    protected function getDefaultValue()
    {
        $const = $this->getConstList(true);

        if (!array_key_exists($this->defaultConstantName, $const)) {
            throw EnumException::becauseNoDefaultValue(get_called_class());
        }

        return $const[$this->defaultConstantName];
    }

    /**
     * @param mixed $value
     *
     * @throws EnumException
     */
    private function assertValue($value)
    {
        $const = $this->getConstList(true);

        $defaultValueUsed = $value === null && isset($const[$this->defaultConstantName]);

        if (!$defaultValueUsed && !in_array($value, $const, true)) {
            throw EnumException::becauseUnrecognisedValue(get_called_class(), $value);
        }
    }
}
