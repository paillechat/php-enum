<?php

namespace Paillechat\Enum;

use Paillechat\Enum\Exception\EnumException;

trait EnumValueToIntegerTrait
{
    abstract public function getValue();

    /**
     * Get int value, if value integer
     *
     * @return int
     *
     * @throws EnumException
     *
     * @deprecated integer-holding enums are deprecated and will be removed in 2.0
     */
    public function toInt(): int
    {
        trigger_error(
            __METHOD__ . ' is deprecated and will be removed in 2.0. ' .
            'Use string-named enums.',
            E_USER_DEPRECATED
        );

        if (!is_integer($this->getValue())) {
            throw EnumException::becauseValueNotInteger();
        }

        return (int) $this->getValue();
    }
}
