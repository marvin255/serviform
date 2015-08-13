<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * List of radio buttons class
 */
class RadioList extends \serviform\BaseRenderable
{
	/**
	 * @var bool
	 */
	public $multiple = false;
	/**
	 * @var array
	 */
	protected $_labelOptions = array();
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
		$value = $this->getValue();
		$options = $this->getAttributes();
		$options['name'] = $this->getNameChainString();
		$isMultiple = $this->multiple;
		$content = '';
		foreach ($list as $optionValue => $optionContent) {
			$optionOptions = $options;
			$optionOptions['value'] = $optionValue;
			$optionOptions['type'] = $isMultiple ? 'checkbox' : 'radio';
			$optionOptions['id'] = Html::toId($options['name'] . '_' . $optionValue);
			if (
				(!$isMultiple && $optionValue == $value)
				|| ($isMultiple && is_array($value) && in_array($optionValue, $value))
			){
				$optionOptions['checked'] = 'checked';
			}
			$option = Html::tag('input', $optionOptions, false);
			$labelOptions = $this->getLabelOptions();
			$labelOptions['for'] = $optionOptions['id'];
			$content .= Html::tag('label', $labelOptions, $option . Html::clearText($optionContent));
		}
		return $content;
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
	 * @param array $list
	 */
	public function setLabelOptions(array $list)
	{
		$this->_labelOptions = $list;
	}

	/**
	 * @return array
	 */
	public function getLabelOptions()
	{
		return $this->_labelOptions;
	}
}