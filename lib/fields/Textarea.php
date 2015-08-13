<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Textarea class
 */
class Textarea extends \serviform\BaseRenderable
{
	/**
	 * @return string
	 */
	public function getInput()
	{
		$attrubutes = $this->getAttributes();
		$attrubutes['name'] = $this->getNameChainString();
		return Html::tag('textarea', $attrubutes, Html::clearText($this->getValue()));
	}
}