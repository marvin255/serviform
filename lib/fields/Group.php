<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Fields group class
 */
class Group extends \serviform\FieldBase implements \serviform\IValidateable
{
	use \serviform\traits\Validateable;


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

		return Html::tag('fieldset', $this->getAttributes(), $return);
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
		return $return;
	}


	/**
	 * @var array
	 */
	protected $_itemAttributes = null;

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
