<?php

namespace serviform;

/**
 * Base element class.
 */
abstract class FieldBase implements IElement
{
    use \serviform\traits\Configurable;
    use \serviform\traits\Renderable;
    use \serviform\traits\Attributable;

    /**
     * @var mixed
     */
    protected $_value = null;
    /**
     * @var string
     */
    protected $_name = '';
    /**
     * @var \serviform\IElement
     */
    protected $_parent = null;
    /**
     * @var array
     */
    protected $_errors = array();
    /**
     * @var string
     */
    protected $_label = '';

    /**
     * @return string
     */
    abstract public function getInput();

    /**
     * @param \serviform\IElement $parent
     */
    public function setParent(\serviform\IElement $parent)
    {
        $this->_parent = $parent;
    }

    /**
     * @return \serviform\IElement
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = trim($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return array
     */
    public function getFullName()
    {
        $return = array();
        $parent = $this->getParent();
        if ($parent) {
            $return = $parent->getFullName();
        }
        $name = $this->getName();
        if ($name !== '') {
            $return[] = $name;
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getNameChainString()
    {
        $names = $this->getFullName();
        $return = array_shift($names);
        if (!empty($names)) {
            $return .= '['.implode('][', $names).']';
        }

        return $return;
    }

    /**
     * @param string $error
     */
    public function addError($error)
    {
        $this->_errors[] = trim($error);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    public function clearErrors()
    {
        $this->_errors = array();
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->_label = trim($label);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }
}
