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
     */
    public function toInt(): int
    {
        if (!is_integer($this->getValue())) {
            throw EnumException::becauseValueNotInteger();
        }

        return (int) $this->getValue();
    }
}
