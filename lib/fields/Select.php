<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Select class
 */
class Select extends \serviform\FieldBase
{
	use \serviform\traits\Listable;


	/**
	 * @var string
	 */
	public $prompt = null;


	/**
	 * @return string
	 */
	public function getInput()
	{
		$res = $this->renderTemplate();
		if ($res !== null) return $res;

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
				$optionValue !== ''
				&& $value !== null
				&& (
					(!$isMultiple && $optionValue == $value) 
					|| ($isMultiple && is_array($value) && in_array($optionValue, $value))
				)
			){
				$optionOptions['selected'] = 'selected';
			}
			$content .= Html::tag('option', $optionOptions, Html::clearText($optionContent));
		}

		return Html::tag('select', $options, $content);
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