<?php

namespace Paillechat\Enum\Exception;

class EnumException extends \Exception
{
    /**
     * @param string $enum
     * @param string $value
     *
     * @return EnumException
     */
    public static function becauseUnrecognisedValue(string $enum, string $value): EnumException
    {
        return new self(sprintf('Value %s not exist in enum %s', $value, $enum));
    }

    /**
     * @param string $enum
     *
     * @return EnumException
     */
    public static function becauseNoDefaultValue(string $enum): EnumException
    {
        return new self(sprintf('No default value in %s enum', $enum));
    }

    /**
     * @return EnumException
     */
    public static function becauseValueNotInteger()
    {
        return new self(sprintf('Value no mismatch integer type'));
    }
}
