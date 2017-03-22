<?php

namespace marvin255\serviform\helpers;

use marvin255\serviform\traits\Factory;

/**
 * Factory for validators.
 */
class FactoryValidators
{
    use Factory;

    /**
     * @return array
     */
    protected static function loadDefaultDescriptions()
    {
        return [
            'compare' => ['type' => '\\marvin255\\serviform\\validators\\Compare'],
            'defaultValue' => ['type' => '\\marvin255\\serviform\\validators\\DefaultValue'],
            'filter' => ['type' => '\\marvin255\\serviform\\validators\\Filter'],
            'range' => ['type' => '\\marvin255\\serviform\\validators\\Range'],
            'regexp' => ['type' => '\\marvin255\\serviform\\validators\\Regexp'],
            'required' => ['type' => '\\marvin255\\serviform\\validators\\Required'],
            'file' => ['type' => '\\marvin255\\serviform\\validators\\File'],
        ];
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected static function checkClass($class)
    {
        return is_subclass_of($class, '\\marvin255\\serviform\\interfaces\\Validator');
    }
}
