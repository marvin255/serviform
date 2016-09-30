<?php

namespace serviform\helpers;

/**
 * Factory for validators.
 */
class FactoryValidators
{
    /**
     * @param string $type
     * @param array  $options
     *
     * @return \serviform\IElement
     */
    protected static function initElement($type, array $options = array())
    {
        if (strpos($type, '\\') !== false) {
            $class = $type;
        } elseif ($description = self::getValidatorDescription($type)) {
            $class = $description['type'];
            unset($description['type']);
            $options = array_merge($description, $options);
        } else {
            $class = $type;
        }
        if (is_subclass_of($class, '\\serviform\\IValidator')) {
            $item = new $class();
            $item->config($options);

            return $item;
        } else {
            throw new \serviform\Exception('Wrong class: '.$class);
        }
    }

    /**
     * @param array $options
     *
     * @return \serviform\IElement
     */
    public static function init(array $options)
    {
        if (!empty($options['type'])) {
            $type = $options['type'];
            unset($options['type']);

            return self::initElement($type, $options);
        } else {
            throw new \serviform\Exception('Wrong field type');
        }
    }

    /**
     * @var array
     */
    protected static $_descriptions = [
        'compare' => ['type' => '\serviform\validators\Compare'],
        'defaultValue' => ['type' => '\serviform\validators\DefaultValue'],
        'filter' => ['type' => '\serviform\validators\Filter'],
        'range' => ['type' => '\serviform\validators\Range'],
        'regexp' => ['type' => '\serviform\validators\Regexp'],
        'required' => ['type' => '\serviform\validators\Required'],
    ];

    /**
     * @param string $namespace
     * @param array  $options
     */
    public static function setValidatorDescription($name, array $options)
    {
        if (empty($options['type']) || !class_exists($options['type'])) {
            throw new \serviform\Exception('Class does not exist');
        }
        self::$_descriptions[$name] = $options;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public static function getValidatorDescription($name)
    {
        return isset(self::$_descriptions[$name]) ? self::$_descriptions[$name] : null;
    }
}
