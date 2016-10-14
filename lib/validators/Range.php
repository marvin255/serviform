<?php

namespace serviform\validators;

/**
 * Filter validator class.
 */
class Range extends \serviform\ValidatorBase
{
    /**
     * @var bool
     */
    public $not = false;
    /**
     * @var bool
     */
    public $strict = false;
    /**
     * @var bool
     */
    public $range = null;
    /**
     * @var string
     */
    public $message = 'Field "#label#" is not in range';

    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        if (is_array($this->range) || $this->range instanceof \Traversable) {
            $res = false;
            foreach ($this->range as $test) {
                if (
                    ($this->strict && $value === $test)
                    || (!$this->strict && $value == $test)
                ) {
                    $res = true;
                    break;
                }
            }

            return $this->not ? !$res : $res;
        } else {
            throw new \serviform\Exception('Range param must be instance of an array or Traversable');
        }
    }
}
