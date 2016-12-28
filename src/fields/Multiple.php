<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\traits\HasValidators as TField;
use marvin255\serviform\interfaces\Field;
use marvin255\serviform\interfaces\HasChildren;
use marvin255\serviform\interfaces\HasValidators;
use InvalidArgumentException;

/**
 * Multiple field class.
 */
class Multiple implements Field, HasChildren, HasValidators
{
    use TField {
        setElement as protected traitSetElement;
        getElements as protected traitGetElements;
    }

    /**
     * @return string
     */
    protected function renderInternal()
    {
        $return = '';
        $elements = $this->getElements();
        $itemAttributes = $this->getItemAttributes();
        foreach ($elements as $el) {
            $return .= Html::createTag('div', $itemAttributes, $el->getInput());
        }

        return Html::createTag('div', $this->getAttributes(), $return);
    }

    /**
     * @param string $name
     * @param mixed  $element
     *
     * @throws \InvalidArgumentException
     *
     * @return \marvin255\serviform\interfaces\HasChildren
     */
    public function setElement($name, $element)
    {
        $max = $this->getMax();
        $count = count($this->elements);
        if ($max === null || $count < $max) {
            return $this->traitSetElement($name, $this->getMultiplier());
        } else {
            throw new InvalidArgumentException('Max element count exceeded: '.$max);
        }
    }

    /**
     * @return array
     */
    public function getElements()
    {
        $min = $this->getMin();
        $count = count($this->elements);
        if ($count < $min) {
            for ($i = $count + 1; $i <= $min; ++$i) {
                $this->setElement($i, null);
            }
        }

        return $this->traitGetElements();
    }

    /**
     * @var int
     */
    protected $min = null;

    /**
     * @param int $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setMin($value)
    {
        $this->min = $value === null ? null : (int) $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @var int
     */
    protected $max = null;

    /**
     * @param int $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setMax($value)
    {
        $this->max = $value === null ? null : (int) $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @var int
     */
    protected $multiplier = null;

    /**
     * @param array $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setMultiplier(array $value)
    {
        $this->multiplier = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getMultiplier()
    {
        return $this->multiplier;
    }

    /**
     * @var array
     */
    protected $itemAttributes = [];

    /**
     * @param array $element
     */
    public function setItemAttributes(array $element)
    {
        $this->itemAttributes = $element;
    }

    /**
     * @return array
     */
    public function getItemAttributes()
    {
        return $this->itemAttributes;
    }
}
