<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Button class
 */
class Button extends \serviform\BaseRenderable
{
	use \serviform\traits\Renderable;



	/**
	 * @var bool
	 */
	public $allowHtmlContent = false;



	/**
	 * @return string
	 */
	public function getInput()
	{
		$res = $this->renderTemplate();
		if ($res !== null) return $res;

		$options = $this->getAttributes();
		
		return Html::tag(
			'button', 
			$options, 
			$this->allowHtmlContent ? $this->getLabel() : Html::clearText($this->getLabel())
		);
	}
}