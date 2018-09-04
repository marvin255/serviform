<?php

namespace marvin255\serviform\validators;

use marvin255\serviform\abstracts\Validator;
use InvalidArgumentException;

/**
 * Compare validator class.
 */
class Compare extends Validator
{
    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        $toTest = $this->getCompareValue();
        if ($this->getCompareAttribute()) {
            $testElement = $element->getParent()->getElement($this->getCompareAttribute());
            if (!$testElement) {
                throw new InvalidArgumentException('Wrong attribute');
            }
            $toTest = $testElement->getValue();
        }
        $return = false;
        switch ($this->getOperator()) {
            case '!=':
                $return = $value != $toTest;
            break;
            case '!==':
                $return = $value !== $toTest;
            break;
            case '>':
                $return = $value > $toTest;
            break;
            case '>=':
                $return = $value >= $toTest;
            break;
            case '<':
                $return = $value < $toTest;
            break;
            case '<=':
                $return = $value <= $toTest;
            break;
            case '===':
                $return = $value === $toTest;
            break;
            case '==':
            default:
                $return = $value == $toTest;
            break;
        }

        return $return;
    }

    /**
     * @var string
     */
    protected $operator = '==';

    /**
     * @param string $value
     *
     * @return \marvin255\serviform\validators\Compare
     */
    public function setOperator($value)
    {
        $this->operator = trim($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @var string
     */
    public $compareAttribute = null;

    /**
     * @param string $value
     *
     * @return \marvin255\serviform\validators\Compare
     */
    public function setCompareAttribute($value)
    {
        $this->compareAttribute = trim($value) === '' ? null : trim($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getCompareAttribute()
    {
        return $this->compareAttribute;
    }

    /**
     * @var mixed
     */
    public $compareValue = null;

    /**
     * @param string $value
     *
     * @return \marvin255\serviform\validators\Compare
     */
    public function setCompareValue($value)
    {
        $this->compareValue = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompareValue()
    {
        return $this->compareValue;
    }
}
