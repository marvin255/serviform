<?php

namespace serviform\traits;

/**
 * Trait for items which have children
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
		if (!is_array($value)) return;
		foreach ($value as $key => $value) {
			$element = $this->getElement($key);
			if ($element) $element->setValue($value);
		}
	}

	/**
	 * @return array
	 */
	public function getValue()
	{
		$return = array();
		foreach ($this->getElements() as $element) {
			$return[$element->getName()] = $element->getValue();
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
		foreach ($elements as $name => $element) {
			$this->setElement($name, $element);
		}
	}

	/**
	 * @param string $name
	 * @param array|\serviform\interfaces\FormComponent $element
	 */
	public function setElement($name, $element)
	{
		$config = $element;
		$config['parent'] = $this;
		$config['name'] = $name;
		$element = $this->createElement($config);
		$this->_elements[$name] = $element;
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
		if (isset($this->_elements[$name])) unset($this->_elements[$name]);
	}

	/**
	 * @param array $options
	 */
	protected function createElement(array $options)
	{
		return \serviform\helpers\FactoryFields::init($options);
	}
}
