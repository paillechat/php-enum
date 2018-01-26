<?php

namespace Paillechat\Enum;

abstract class StaticEnum extends Enum
{
    /** @var StaticEnum[][] */
    private static $instances = [];

    private static $constReflections = [];

    final protected static function createNamedInstance(string $name, $value)
    {
        $class = self::findParentClassForConst($name);

        $key = self::getConstKey($class, $name);

        if (!array_key_exists($key, self::$instances)) {
            self::$instances[$key] = parent::createNamedInstance($name, $value);
        }

        return self::$instances[$key];
    }

    private static function getConstantReflection(string $class, string $name): \ReflectionClassConstant
    {
        $key = self::getConstKey($class, $name);
        if (!array_key_exists($key, self::$constReflections)) {
            $refl = self::getEnumReflection(static::class);

            self::$constReflections[$key] = $refl->getReflectionConstant($name);
        }

        return self::$constReflections[$key];
    }

    private static function getConstKey(string $class, string $name): string
    {
        return $class.'::'.$name;
    }

    private static function findParentClassForConst(string $name): string
    {
        return self::getConstantReflection(static::class, $name)->getDeclaringClass()->getName();
    }
}
