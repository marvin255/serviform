<?php

namespace serviform\validators;

/**
 * Default validator class.
 */
class DefaultValue extends \serviform\ValidatorBase
{
    /**
     * @var mixed
     */
    public $value = '';

    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        if ($this->isEmpty($value)) {
            $element->setValue($this->value);
        }

        return true;
    }
}
