<?php

namespace marvin255\serviform\abstracts;

use InvalidArgumentException;
use marvin255\serviform\helpers\FactoryFields;
use marvin255\serviform\interfaces\HasChildren;

/**
 * An abstract class for field that has children.
 */
abstract class FieldHasChildren extends Field implements HasChildren
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @param array $elements
     *
     * @return \marvin255\serviform\interfaces\HasChildren
     */
    public function setElements(array $elements)
    {
        $this->elements = [];
        foreach ($elements as $name => $element) {
            $this->setElement($name, $element);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $element
     * @param int    $position
     *
     * @throws \InvalidArgumentException
     *
     * @return \marvin255\serviform\interfaces\HasChildren
     */
    public function setElement($name, $element, $position = null)
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Empty child name');
        } elseif (is_array($element)) {
            $config = $element;
            $config['parent'] = $this;
            $config['name'] = $name;
            $element = $this->createElement($config);
        } elseif (true === ($element instanceof \marvin255\serviform\interfaces\Field)) {
            $element->setName($name);
            $element->setParent($this);
        } else {
            throw new InvalidArgumentException('Wrong child type for field: ' . $name);
        }
        if ($position === null) {
            $this->elements[$name] = $element;
        } else {
            $elements = [];
            $itemPosition = $position < 0 ? count($this->elements) + $position : $position;
            $i = 0;
            foreach ($this->elements as $iKey => $iElement) {
                if ($i === $itemPosition) {
                    $elements[$name] = $element;
                }
                $elements[$iKey] = $iElement;
                ++$i;
            }
            $this->elements = $elements;
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
        $type = isset($options['type']) ? $options['type'] : null;
        unset($options['type']);

        return FactoryFields::initElement($type, $options);
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param string $name
     *
     * @return \marvin255\serviform\interfaces\Field|null
     */
    public function getElement($name)
    {
        $elements = $this->getElements();

        return isset($elements[$name]) ? $elements[$name] : null;
    }

    /**
     * @param string $name
     *
     * @return \marvin255\serviform\interfaces\HasChildren
     */
    public function unsetElement($name)
    {
        if (isset($this->elements[$name])) {
            unset($this->elements[$name]);
        }

        return $this;
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
            if (!$this->getElement($key)) {
                continue;
            }
            $this->getElement($key)->setValue($value);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $return = [];
        foreach ($this->getElements() as $name => $element) {
            $return[$name] = $element->getValue();
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function addError($error)
    {
        if (is_array($error)) {
            foreach ($error as $key => $value) {
                $element = $this->getElement($key);
                $element->addError($value);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        $return = [];
        foreach ($this->getElements() as $name => $element) {
            if (!$element->getErrors()) {
                continue;
            }
            $return[$name] = $element->getErrors();
        }

        return $return;
    }

    /**
     * @return \marvin255\serviform\interfaces\Field
     */
    public function clearErrors()
    {
        foreach ($this->getElements() as $element) {
            $element->clearErrors();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $return = parent::jsonSerialize();
        $return['elements'] = [];
        $elements = $this->getElements();
        foreach ($elements as $name => $element) {
            $return['elements'][$name] = $element->jsonSerialize();
        }

        return $return;
    }
}
