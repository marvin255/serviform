<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Form class
 */
class Form extends \serviform\FieldBase implements \serviform\IValidateable
{
	use \serviform\traits\Validateable;


	/**
	 * @return string
	 */
	public function getInput()
	{
		if ($this->getTemplate() === null) {
			$this->setTemplate(__DIR__ . '/../views/bootstrap_3.php');
		}
		return $this->renderTemplate();
	}

	/**
	 * @return string
	 */
	public function getBeginTag()
	{
		$attrubutes = $this->getAttributes();
		return Html::tag('form', $attrubutes, true);
	}

	/**
	 * @return string
	 */
	public function getEndTag()
	{
		return '</form>';
	}

	/**
	 * @param array $values
	 */
	public function loadData(array $values = null)
	{
		$valueSet = false;
		$values = is_array($values) ? $values : $_REQUEST;
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
			$elements = $this->getElements();
			foreach ($values as $key => $v) {
				if (isset($elements[$key])) {
					$valueSet = true;
					break;
				}
			}
		}
		return $valueSet;
	}
}
