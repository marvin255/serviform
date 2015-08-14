<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Multiple elements class
 */
class Multiple extends \serviform\BaseRenderable
{
	/**
	 * @var int
	 */
	public $min = 1;
	/**
	 * @var int
	 */
	public $max = null;
	/**
	 * @var array
	 */
	protected $_multiplier = null;
	/**
	 * @var array
	 */
	protected $_elements = null;
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
			$return .= Html::tag('div', $itemAttributes, $el->getInput());
		}

		return Html::tag('div', $this->getAttributes(), $return);
	}



	/**
	 * @param array $value
	 */
	public function setValue($value)
	{
		if (is_array($value)) {
			$i = 0;
			foreach ($value as $val) {
				$this->setElementValue($i, $val);
				$i++;
			}
		}
	}

	/**
	 * @return array
	 */
	public function getValue()
	{
		$return = array();
		$elements = $this->getElements();
		foreach ($elements as $key => $element) {
			$value = $element->getValue();
			if ($value !== null && $value !== array())
				$return[$key] = $value;
		}
		return $return;
	}



	/**
	 * @return array
	 */
	public function getElements()
	{
		if ($this->_elements === null) {
			$min = intval($this->min);
			$min = $min ? $min : 1;
			for ($i = 0; $i < $min; $i++) {
				$this->setElementValue($i, null);
			}
		}
		return $this->_elements;
	}

	/**
	 * @param int $key
	 * @param mixed $value
	 * @return \serviform\IElement
	 */
	protected function setElementValue($key, $value)
	{
		$elements = $this->_elements;
		$count = count($elements);
		$max = $this->max === null ? null : intval($this->max);
		if (isset($elements[$key])) {
			$elements[$key]->setValue($value);
		} elseif ($max === null || $count <= $max) {
			$options = $this->getMultiplier();
			$options['name'] = $key;
			$options['parent'] = $this;
			$item = \serviform\helpers\Factory::init($options);
			$item->setValue($value);
			$this->_elements[$key] = $item;
		}
	}



	/**
	 * @param array $element
	 */
	public function setMultiplier(array $element)
	{
		$this->_multiplier = $element;
	}

	/**
	 * @return array
	 */
	public function getMultiplier()
	{
		return $this->_multiplier;
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