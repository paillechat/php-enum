<?php

namespace Paillechat\Enum\Exception;

class EnumException extends \Exception
{
    /**
     * @param string $enum
     * @param string $member
     *
     * @return EnumException
     */
    public static function becauseUnknownMember(string $enum, string $member): EnumException
    {
        return new self(sprintf('Unknown member "%s" for enum %s', $member, $enum));
    }
}
