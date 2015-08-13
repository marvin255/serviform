<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Input class
 */
class Input extends \serviform\BaseRenderable
{
	/**
	 * @return string
	 */
	public function getInput()
	{
		$attrubutes = $this->getAttributes();
		$attrubutes['value'] = $this->getValue();
		$attrubutes['name'] = $this->getNameChainString();
		if (empty($attrubutes['type'])) {
			$attrubutes['type'] = 'text';
		}
		return Html::tag('input', $attrubutes, false);
	}
}