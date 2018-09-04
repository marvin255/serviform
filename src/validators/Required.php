<?php

namespace marvin255\serviform\validators;

use marvin255\serviform\abstracts\Validator;

/**
 * Require validator class.
 */
class Required extends Validator
{
    /**
     * @var string
     */
    protected $message = 'Field "#label#" is required';

    /**
     * @param mixed                                 $value
     * @param \marvin255\serviform\interfaces\Field $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        return !$this->isEmpty($value);
    }
}
