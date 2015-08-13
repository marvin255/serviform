<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Button class
 */
class Button extends \serviform\BaseRenderable
{
	/**
	 * @var bool
	 */
	public $allowHtmlContent = false;



	/**
	 * @return string
	 */
	public function getInput()
	{
		$options = $this->getAttributes();
		return Html::tag(
			'button', 
			$options, 
			$this->allowHtmlContent ? $this->getLabel() : Html::clearText($this->getLabel())
		);
	}
}