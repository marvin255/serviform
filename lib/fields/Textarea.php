<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Textarea class
 */
class Textarea extends \serviform\FieldBase
{
	/**
	 * @return string
	 */
	public function getInput()
	{
		$res = $this->renderTemplate();
		if ($res !== null) return $res;

		$attrubutes = $this->getAttributes();
		$attrubutes['name'] = $this->getNameChainString();

		return Html::tag('textarea', $attrubutes, Html::clearText($this->getValue()));
	}
}