<?php

namespace marvin255\serviform\validators;

use InvalidArgumentException;
use marvin255\serviform\abstracts\Validator;

/**
 * Range validator class.
 */
class Range extends Validator
{
    /**
     * @var string
     */
    protected $message = 'Field "#label#" is not in range';

    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        $res = false;
        foreach ($this->getRange() as $test) {
            if (
                ($this->getStrict() && $value === $test)
                || (!$this->getStrict() && $value == $test)
            ) {
                $res = true;
                break;
            }
        }

        return $this->getNot() ? !$res : $res;
    }

    /**
     * @var bool
     */
    protected $not = false;

    /**
     * @param bool $value
     *
     * @return \marvin255\serviform\validators\Range
     */
    public function setNot($value)
    {
        $this->not = (bool) $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getNot()
    {
        return $this->not;
    }

    /**
     * @var bool
     */
    protected $strict = false;

    /**
     * @param bool $value
     *
     * @return \marvin255\serviform\validators\Range
     */
    public function setStrict($value)
    {
        $this->strict = (bool) $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getStrict()
    {
        return $this->strict;
    }

    /**
     * @var array
     */
    protected $range = null;

    /**
     * @param array $value
     *
     * @return \marvin255\serviform\validators\Range
     */
    public function setRange($value)
    {
        if (is_array($value) || $value instanceof \Traversable) {
            $this->range = $value;
        } else {
            throw new InvalidArgumentException('Range param must be instance of an array or Traversable');
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getRange()
    {
        return $this->range;
    }
}
