<?php

namespace marvin255\serviform\fields;

use InvalidArgumentException;
use marvin255\serviform\abstracts\FieldHasValidators;
use marvin255\serviform\helpers\Html;

/**
 * Multiple field class.
 */
class Multiple extends FieldHasValidators
{
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
     * @inheritdoc
     */
    public function setElement($name, $element, $position = null)
    {
        $max = $this->getMax();
        $count = count($this->elements);
        $name = isset($this->elements[$name]) ? (int) $name : $count;
        if (!isset($this->elements[$name]) && $max !== null && $count >= $max) {
            throw new InvalidArgumentException('Max element count exceeded: ' . $max);
        } else {
            parent::setElement($name, $element === null ? [] : $element);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        $min = $this->getMin();
        $count = count($this->elements);
        if ($min !== null && $count < $min) {
            for ($i = $count + 1; $i <= $min; ++$i) {
                $this->setElement($i, null);
            }
        }

        return parent::getElements();
    }

    /**
     * @param mixed $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setValue($value)
    {
        if (!is_array($value)) {
            $value = [];
        }
        foreach ($value as $key => $value) {
            $element = $this->getElement($key);
            if (!$element) {
                $this->setElement($key, ['value' => $value]);
            } else {
                $element->setValue($value);
            }
        }

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function createElement(array $options)
    {
        $options = array_merge($options, $this->getMultiplier());

        return parent::createElement($options);
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
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setItemAttributes(array $element)
    {
        $this->itemAttributes = $element;

        return $this;
    }

    /**
     * @return array
     */
    public function getItemAttributes()
    {
        return $this->itemAttributes;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $return = parent::jsonSerialize();
        $return['min'] = $this->getMin();
        $return['max'] = $this->getMax();
        $return['itemAttributes'] = $this->getItemAttributes();

        return $return;
    }
}
