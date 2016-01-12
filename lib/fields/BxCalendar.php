<?php

namespace serviform\fields;

use \serviform\helpers\Html;
use CFileMan;

/**
 * Bitrix calendar with editor class
 */
class BxCalendar extends \serviform\FieldBase
{
	/**
	 * @var string
	 */
	public $size = 19;



	/**
	 * @return string
	 */
	public function getInput()
	{
		\CJSCore::Init(array('date'));
		return \CAdminCalendar::CalendarDate(
			$this->getNameChainString(), 
			$this->getValue(),
			$this->size,
			true
		);
	}
}