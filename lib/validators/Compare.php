<?php

namespace serviform\validators;

/**
 * Compare validator class.
 */
class Compare extends \serviform\ValidatorBase
{
    /**
     * @var string
     */
    public $operator = '==';
    /**
     * @var string
     */
    public $compareAttribute = null;
    /**
     * @var mixed
     */
    public $compareValue = null;

    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        $toTest = $this->compareValue;
        if ($this->compareAttribute) {
            $testElement = $element->getParent()->getElement($this->compareAttribute);
            if (!$testElement) {
                throw new \serviform\Exception('Wrong attribute');
            }
            $toTest = $testElement->getValue();
        }
        switch ($this->operator) {
            case '!=':
                return $value != $toTest;
            break;
            case '!==':
                return $value !== $toTest;
            break;
            case '>':
                return $value > $toTest;
            break;
            case '>=':
                return $value >= $toTest;
            break;
            case '<':
                return $value < $toTest;
            break;
            case '<=':
                return $value <= $toTest;
            break;
            case '==':
            default:
                return $value == $toTest;
            break;
        }
    }
}
