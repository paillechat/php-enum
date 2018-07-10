<?php

namespace Paillechat\Enum;

use Paillechat\Enum\Exception\EnumException;

abstract class Enum
{
    /** @var Enum[] */
    private static $instances = [];
    /** @var \ReflectionClassConstant[] */
    private static $constReflections = [];
    /** @var \ReflectionClass[] */
    private static $reflections = [];
    /** @var string */
    private $name;

    /**
     * @param string $name
     */
    final private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Creates enum instance by name
     *
     * @param string $name
     *
     * @return static
     *
     * @throws EnumException
     */
    final public static function createByName(string $name)
    {
        $canonicalName = strtoupper($name);
        if ($canonicalName !== $name) {
            $name = $canonicalName;
            trigger_error('PSR-1 requires constant to be declared in upper case.', E_USER_NOTICE);
        }

        $const = static::getConstList();

        if (!\in_array($name, $const, true)) {
            throw EnumException::becauseUnknownMember(static::class, $name);
        }

        return static::createNamedInstance($name);
    }

    /**
     * Creates enum instance with short static constructor
     *
     * @param string $name
     * @param array $arguments
     *
     * @return static
     *
     * @throws EnumException
     */
    final public static function __callStatic(string $name, array $arguments)
    {
        return static::createByName($name);
    }

    public static function getConstList(): array
    {
        return array_keys(self::getEnumReflection(static::class)->getConstants());
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
        return $class . '::' . $name;
    }

    private static function findParentClassForConst(string $name): string
    {
        return self::getConstantReflection(static::class, $name)->getDeclaringClass()->getName();
    }

    private static function getEnumReflection(string $class): \ReflectionClass
    {
        if (!array_key_exists($class, self::$reflections)) {
            try {
                self::$reflections[$class] = new \ReflectionClass($class);
                // @codeCoverageIgnoreStart
            } catch (\ReflectionException $e) {
                throw new \LogicException('Class should be valid FQCN. Fix internal calls.');
                // @codeCoverageIgnoreEnd
            }
        }

        return self::$reflections[$class];
    }

    /**
     * Create named enum instance
     *
     * @param string $name
     *
     * @return static
     */
    private static function createNamedInstance(string $name)
    {
        $class = self::findParentClassForConst($name);

        $key = self::getConstKey($class, $name);

        if (!array_key_exists($key, self::$instances)) {
            self::$instances[$key] = new static($name);
        }

        return self::$instances[$key];
    }

    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    final public function __toString(): string
    {
        return $this->getName();
    }
}
