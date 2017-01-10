<?php

namespace marvin255\serviform\validators;

use marvin255\serviform\abstracts\Validator;

/**
 * Filter validator class.
 */
class Filter extends Validator
{
    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        if ($this->getFilter()) {
            if (is_string($this->getFilter())) {
                $f = $this->getFilter();
                $element->setValue($f($element->getValue()));
            } elseif (is_callable($this->getFilter())) {
                return call_user_func_array($this->getFilter(), [$value, $element]);
            }
        }

        return true;
    }

    /**
     * @var string
     */
    protected $filter = null;

    /**
     * @param mixed $value
     *
     * @return \marvin255\serviform\validators\Filter
     */
    public function setFilter($value)
    {
        $this->filter = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
