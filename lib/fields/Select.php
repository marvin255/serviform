<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Select class
 */
class Select extends \serviform\BaseRenderable
{
	/**
	 * @var string
	 */
	public $prompt = null;
	/**
	 * @var array
	 */
	protected $_list = array();



	/**
	 * @return string
	 */
	public function getInput()
	{
		$list = $this->getList();
		if (!empty($this->prompt)) {
			$oldList = $list;
			$list = array('' => $this->prompt);
			foreach ($oldList as $key => $val) $list[$key] = $val;
		}
		$value = $this->getValue();
		$options = $this->getAttributes();
		$options['name'] = $this->getNameChainString();
		$isMultiple = $this->isMultiple();
		$content = '';
		foreach ($list as $optionValue => $optionContent) {
			$optionOptions = array('value' => $optionValue);
			if (
				(!$isMultiple && $optionValue == $value) 
				|| ($isMultiple && is_array($value) && in_array($optionValue, $value))
			){
				$optionOptions['selected'] = 'selected';
			}
			$content .= Html::tag('option', $optionOptions, Html::clearText($optionContent));
		}
		return Html::tag('select', $options, $content);
	}



	/**
	 * @param array $list
	 */
	public function setList(array $list)
	{
		$this->_list = $list;
	}

	/**
	 * @return array
	 */
	public function getList()
	{
		return $this->_list;
	}



	/**
	 * @return string
	 */
	public function getNameChainString()
	{
		$return = parent::getNameChainString();
		if ($this->isMultiple()) {
			$return .= '[]';
		}
		return $return;
	}



	/**
	 * @return bool
	 */
	protected function isMultiple()
	{
		$multiple = $this->getAttribute('multiple');
		return $multiple !== null;
	}
}