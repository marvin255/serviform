<?php

namespace marvin255\serviform\traits;

use InvalidArgumentException;

/**
 * Trait for factory classes.
 */
trait Factory
{
    /**
     * @param string $type
     * @param array  $options
     *
     * @throws \InvalidArgumentException
     *
     * @return \serviform\IElement
     */
    public static function initElement($type, array $options = [])
    {
        if (strpos($type, '\\') !== false) {
            $class = $type;
        } elseif ($description = self::getDescription($type)) {
            $class = $description['type'];
            unset($description['type']);
            $options = array_merge($description, $options);
        } elseif (!empty($type)) {
            $class = $type;
        }
        if (self::checkClass($class)) {
            $item = self::configObject(new $class(), $options);
        } else {
            throw new InvalidArgumentException('Wrong class: ' . $class);
        }

        return $item;
    }

    /**
     * @param mixed $object
     * @param array $options
     *
     * @return mixed
     */
    protected static function configObject($object, array $options)
    {
        $options = self::checkOptions($options);
        foreach ($options as $name => $value) {
            $methodName = 'set' . ucfirst($name);
            if (!method_exists($object, $methodName)) {
                continue;
            }
            $object->$methodName($value);
        }

        return $object;
    }

    /**
     * @var array
     */
    protected static $descriptions = null;

    /**
     * @param string $namespace
     * @param array  $options
     * @param bool   $mergeOptions
     *
     * @throws \InvalidArgumentException
     */
    public static function setDescription($name, array $options, $mergeOptions = false)
    {
        if (self::$descriptions === null) {
            self::$descriptions = self::loadDefaultDescriptions();
        }
        if ($mergeOptions && !empty(self::$descriptions[$name])) {
            $options = array_merge(self::$descriptions[$name], $options);
        }
        if (empty($options['type'])) {
            throw new InvalidArgumentException('Type parameter is empty');
        } elseif (!class_exists($options['type'])) {
            throw new InvalidArgumentException('Class does not exist: ' . $options['type']);
        }
        self::$descriptions[$name] = $options;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public static function getDescription($name)
    {
        if (self::$descriptions === null) {
            self::$descriptions = self::loadDefaultDescriptions();
        }

        return isset(self::$descriptions[$name]) ? self::$descriptions[$name] : null;
    }
}
