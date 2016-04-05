<?php

namespace serviform;

/**
 * Base element class
 */
abstract class FieldBase implements IElement
{
	use \serviform\traits\Configurable;
	use \serviform\traits\Renderable;


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
	protected $_attributes = array();
	/**
	 * @var array
	 */
	protected $_errors = array();
	/**
	 * @var string
	 */
	protected $_label = '';
	/**
	 * @var bool
	 */
	protected $_useFlatNames = false;
	/**
	 * @var string
	 */
	protected $_flatNamesDelimiter = '___';


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
	 * @param mixed $value
	 */
	public function setAttribute($name, $value)
	{
		$this->_attributes[$name] = $value;
	}

	/**
	 * @param array $attributes
	 */
	public function setAttributes(array $attributes)
	{
		foreach ($attributes as $name => $value) {
			$this->setAttribute($name, $value);
		}
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name)
	{
		return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->_attributes;
	}


	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		if (is_array($name)) {
			$this->_name = array_map('trim', $name);
		} else {
			$this->_name = trim($name);
		}
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
		if (is_array($name)) {
			$return = array_merge($return, $name);
		} elseif ($name !== '') {
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
			if ($this->getUseFlatNames()) {
				$return .= $this->getFlatNamesDelimiter() . implode($this->getFlatNamesDelimiter(), $names);
			} else {
				$return .= '[' . implode('][', $names) . ']';
			}
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

	/**
	 * @return null
	 */
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


	/**
	 * @param bool $useFlatNames
	 */
	public function setUseFlatNames($useFlatNames)
	{
		$this->_useFlatNames = (bool) $useFlatNames;
	}

	/**
	 * @return bool
	 */
	public function getUseFlatNames()
	{
		$parent = $this->getParent();
		return $parent ? $parent->getUseFlatNames() : $this->_useFlatNames;
	}


	/**
	 * @param string $delimiter
	 */
	public function setFlatNamesDelimiter($delimiter)
	{
		$this->_flatNamesDelimiter = trim($delimiter);
	}

	/**
	 * @return string
	 */
	public function getFlatNamesDelimiter()
	{
		$parent = $this->getParent();
		return $parent ? $parent->getFlatNamesDelimiter() : $this->_flatNamesDelimiter;
	}
}
