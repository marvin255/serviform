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
    protected function loadDefaultDescriptions()
    {
        return [
            'compare' => ['type' => '\\serviform\\validators\\Compare'],
            'defaultValue' => ['type' => '\\serviform\\validators\\DefaultValue'],
            'filter' => ['type' => '\\serviform\\validators\\Filter'],
            'range' => ['type' => '\\serviform\\validators\\Range'],
            'regexp' => ['type' => '\\serviform\\validators\\Regexp'],
            'required' => ['type' => '\\serviform\\validators\\Required'],
        ];
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function checkClass($class)
    {
        return is_subclass_of($class, '\\marvin255\\serviform\\interfaces\\Validator');
    }
}
