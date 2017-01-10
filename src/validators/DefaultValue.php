<?php

namespace marvin255\serviform\validators;

use marvin255\serviform\abstracts\Validator;

/**
 * Default validator class.
 */
class DefaultValue extends Validator
{
    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        if ($this->isEmpty($value)) {
            $element->setValue($this->getValue());
        }

        return true;
    }

    /**
     * @var mixed
     */
    protected $value = '';

    /**
     * @param mixed $value
     *
     * @return \marvin255\serviform\validators\DefaultValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
