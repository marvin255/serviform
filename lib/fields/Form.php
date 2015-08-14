<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Form class
 */
class Form extends \serviform\BaseRenderable
{
	/**
	 * @var array поля формы
	 */
	protected $_elements = array();
	/**
	 * @var array
	 */
	protected $_itemAttributes = null;



	/**
	 * @return string
	 */
	public function getInput()
	{
		$res = parent::getInput();
		if ($res !== null) return $res;

		$return = '';
		$elements = $this->getElements();
		$itemAttributes = $this->getItemAttributes();
		foreach ($elements as $el) {
			$label = Html::tag('label', array(), Html::clearText($el->getLabel()));
			$input = $el->getInput();
			$return .= Html::tag('div', $itemAttributes, "{$label}{$input}");
		}

		$tag = $this->getParent() ? 'div' : 'form';
		return Html::tag($tag, $this->getAttributes(), $return);
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



	/**
	 * @param array $element
	 */
	public function setItemAttributes(array $element)
	{
		$this->_itemAttributes = $element;
	}

	/**
	 * @return array
	 */
	public function getItemAttributes()
	{
		return $this->_itemAttributes;
	}
}