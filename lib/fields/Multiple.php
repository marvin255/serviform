<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Multiple elements class
 */
class Multiple extends \serviform\FieldBase implements \serviform\IValidateable
{
	use \serviform\traits\Validateable {
        setValue as traitSetValue;
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
	 * @param array $element
	 */
	public function setElement($name, $element)
	{
		$max = (int) $this->max;
		$elements = $this->_elements;
		if (!$max || count($elements) <= $max) {
			$element = $this->returnElement($name, $element);
			$this->_elements[$name] = $element;
		}
	}

	/**
	 * @param string $name
	 * @param array $element
	 * @return \serviform\IElement
	 */
	public function returnElement($name, $element)
	{
		$element = is_array($element) ? $element : array();
		$config = array_merge($this->getMultiplier(), $element);
		$config['parent'] = $this;
		$config['name'] = $name;
		return $this->createElement($config);
	}


	/**
	 * @param array $value
	 */
	public function setValue($value)
	{
		if (!is_array($value)) return;
		$i = -1;
		if ($this->getUseFlatNames()) {
			$flatDelimiter = $this->getFlatNamesDelimiter();
			$curr = null;
			$set = array();
			foreach ($value as $key => $v) {
				if (preg_match('/^(\d+)' . preg_quote($flatDelimiter) . '(.+)$/', $key, $matches)) {
					if (intval($matches[1]) !== $curr) {
						$i++;
						$curr = intval($matches[0]);
					}
					$set[$i][$matches[2]] = $v;
					unset($value[$key]);
				}
			}
			foreach ($set as $key => $v) {
				$this->setElement($key, null);
				$this->getElement($key)->setValue($v);
			}
		}
		foreach ($value as $key => $v) {
			if (!is_numeric($key)) continue;
			$i++;
			$this->setElement($i, null);
			$this->getElement($i)->setValue($v);
		}
		$this->traitSetValue($value);
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
