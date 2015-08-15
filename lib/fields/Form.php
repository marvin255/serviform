<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Form class
 */
class Form extends \serviform\BaseRenderable
{
	/**
	 * @var array
	 */
	protected $_elements = array();
	/**
	 * @var array
	 */
	protected $_buttons = array();



	/**
	 * @return string
	 */
	public function getInput()
	{
		if ($this->getTemplate() === null) {
			$this->setTemplate(__DIR__ . '/../views/bootstrap.php');
		}
		return parent::getInput();
	}



	/**
	 * @param array $values
	 */
	public function loadData(array $values = null)
	{
		$values = $values ? $values : $_REQUEST;
		$arName = $this->getFullName();
		foreach ($arName as $name) {
			if (isset($values[$name])) {
				$values = $values[$name];
			} else {
				$values = array();
			}
		}
		if (is_array($values)) {
			$this->setValue($values);
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
	 * @param array $options
	 */
	protected function createElement(array $options)
	{
		return \serviform\helpers\Factory::init($options);
	}

	/**
	 * @param string $name
	 */
	public function unsetElement($name)
	{
		if (isset($this->_elements[$name])) unset($this->_elements[$name]);
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
		return isset($this->_elements[$name]) ? $this->_elements[$name] : null;
	}



	/**
	 * @param array $bttons
	 */
	public function setButtons(array $buttons)
	{
		foreach ($buttons as $name => $button) {
			$config = $button;
			$config['parent'] = $this;
			$config['name'] = $name;
			$btn = $this->createElement($config);
			$this->_buttons[$name] = $btn;
		}
	}

	/**
	 * @return array
	 */
	public function getButtons()
	{
		return $this->_buttons;
	}



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
	 * Возвращает список ошибок
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
}