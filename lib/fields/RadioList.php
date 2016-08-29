<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * List of radio buttons class
 */
class RadioList extends \serviform\FieldBase
{
	use \serviform\traits\Listable;


	/**
	 * @var array
	 */
	protected $_labelOptions = array();


	/**
	 * @return string
	 */
	public function getInput()
	{
		$res = $this->renderTemplate();
		if ($res !== null) return $res;

		$list = $this->getList();
		$value = $this->getValue();
		$options = $this->getAttributes();
		$options['name'] = $this->getNameChainString();
		$isMultiple = $this->multiple;
		$content = '';
		$listItemsOptions = $this->getListItemsOptions();
		foreach ($list as $optionValue => $optionContent) {
			$optionOptions = isset($listItemsOptions[$optionValue]) && is_array($listItemsOptions[$optionValue])
				? array_merge($options, $listItemsOptions[$optionValue])
				: $options;
			$optionOptions['value'] = $optionValue;
			$optionOptions['type'] = $isMultiple ? 'checkbox' : 'radio';
			$optionOptions['id'] = Html::toId($options['name'] . '_' . $optionValue);
			if (
				$value !== null
				&& (
					(!$isMultiple && $optionValue == $value)
					|| ($isMultiple && is_array($value) && in_array($optionValue, $value))
				)
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
