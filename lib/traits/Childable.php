<?php

namespace serviform\traits;

/**
 * Trait for items which have children.
 */
trait Childable
{
    /**
     * @var array
     */
    protected $_elements = array();

    /**
     * @param array $value
     */
    public function setValue($value)
    {
        if (!is_array($value)) {
            throw new \serviform\Exception('Value must be an array instance');
        }
        foreach ($value as $key => $value) {
            $element = $this->getElement($key);
            if ($element) {
                $element->setValue($value);
            }
        }
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $return = array();
        if ($this->getElements()) {
            foreach ($this->getElements() as $element) {
                $return[$element->getName()] = $element->getValue();
            }
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        $return = array();
        foreach ($this->getElements() as $element) {
            $errors = $element->getErrors();
            if (!empty($errors)) {
                $return[$element->getName()] = $errors;
            }
        }

        return $return;
    }

    /**
     * @return array
     */
    public function clearErrors()
    {
        foreach ($this->getElements() as $element) {
            $element->clearErrors();
        }
    }

    /**
     * @param array $elements
     */
    public function setElements(array $elements)
    {
        $this->_elements = [];
        foreach ($elements as $name => $element) {
            $this->setElement($name, $element);
        }
    }

    /**
     * @param string                    $name
     * @param array|\serviform\IElement $element
     * @param int $position = null
     */
    public function setElement($name, $element, $position = null)
    {
        if (is_array($element)) {
            $config = $element;
            $config['parent'] = $this;
            $config['name'] = $name;
            $element = $this->createElement($config);
        } elseif ($element instanceof \serviform\IElement) {
            $element->setName($name);
            $element->setParent($this);
        } else {
            throw new \serviform\Exception('Wrong child type');
        }
        if ($position !== null && $position < count($this->_elements)) {
            $newElements = [];
            $i = 0;
            foreach ($this->_elements as $key => $item) {
                if ($i == $position) {
                    $newElements[$name] = $element;
                }
                $newElements[$key] = $item;
                $i++;
            }
            $this->_elements = $newElements;
        } else {
            $this->_elements[$name] = $element;
        }
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->_elements;
    }

    /**
     * @param string $name
     *
     * @return \serviform\interfaces\FormComponent|null
     */
    public function getElement($name)
    {
        $elements = $this->getElements();

        return isset($elements[$name]) ? $elements[$name] : null;
    }

    /**
     * @param string $name
     */
    public function unsetElement($name)
    {
        if (isset($this->_elements[$name])) {
            unset($this->_elements[$name]);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $toJson = parent::jsonSerialize();
        $elements = [];
        foreach ($this->getelements() as $key => $element) {
            $elements[$key] = $element->jsonSerialize();
        }
        $toJson['elements'] = $elements;
        unset($toJson['value']);

        return $toJson;
    }

    /**
     * @param array $options
     */
    protected function createElement(array $options)
    {
        return \serviform\helpers\FactoryFields::init($options);
    }
}
