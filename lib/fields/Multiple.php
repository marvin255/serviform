<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Multiple elements class
 */
class Multiple extends \serviform\FieldBase
{
	use \serviform\traits\Renderable;
	use \serviform\traits\Childable;


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
	protected $_itemAttributes = null;


	/**
	 * @return string
	 */
	public function getInput()
	{
		$res = $this->renderTemplate();
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
	 * @return array
	 */
	public function getElements()
	{
		$count = count($this->_elements);
		$min = (int) $this->min;
		if ($count < $min) {
			for ($i = 0; $i < $min; $i++) {
				$this->setElement($i, array());
			}
		}
		return $this->_elements;
	}

	/**
	 * @param string $name
	 * @param array|\serviform\interfaces\FormComponent $element
	 */
	public function setElement($name, $element)
	{
		$max = (int) $this->max;
		$elements = $this->_elements;
		if (!$max || count($elements) <= $max) {
			$config = $this->getMultiplier();
			$config['parent'] = $this;
			$config['name'] = $name;
			$element = $this->createElement($config);
			$this->_elements[$name] = $element;
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