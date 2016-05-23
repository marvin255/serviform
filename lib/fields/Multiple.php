<?php

namespace serviform\fields;

use \serviform\helpers\Html;
use \serviform\traits\Validateable;

/**
 * Multiple elements class
 */
class Multiple extends \serviform\FieldBase implements \serviform\IValidateable
{
	use Validateable {
		setElement as protected traitSetElement;
		getElement as protected traitGetElement;
		getElements as protected traitGetElements;
		createElement as protected traitCreateElement;
	}


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
		$elements = $this->traitGetElements();
		$count = count($elements);
		$min = (int) $this->min;
		if ($count < $min) {
			for ($i = 0; $i < $min; $i++) $this->setElement($i, []);
			return $this->traitGetElements();
		} else {
			return $elements;
		}
	}

	/**
	 * @param string $name
	 * @return \serviform\interfaces\FormComponent|null
	 */
	public function getElement($name)
	{
		$element = $this->traitGetElement($name);
		if ($element) return $element;
		$name = (int) $name;
		if ($name >= 0 && ($this->max === null || $name < intval($this->max))) {
			$this->setElement($name, []);
			return $this->traitGetElement($name);
		} else {
			return null;
		}
	}

	/**
	 * @param string $name
	 * @param array $element
	 */
	public function setElement($name, $element)
	{
		$name = (int) $name;
		if ($name >= 0 && ($this->max === null || $name < intval($this->max))) {
			$this->traitSetElement($name, $element);
		}
	}


	/**
	 * @param string $name
	 * @param array $element
	 * @return \serviform\IElement
	*/
	protected function createElement($element)
	{
		$element = is_array($element) ? $element : array();
		$multiplier = $this->getMultiplier();
		if (!$multiplier) throw new \serviform\Exception('No multiplier set');
		$config = array_merge($multiplier, $element);
		$config['parent'] = $this;
		return $this->traitCreateElement($config);
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
