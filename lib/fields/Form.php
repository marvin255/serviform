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
}