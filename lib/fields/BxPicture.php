<?php

namespace serviform\fields;

use \serviform\helpers\Html;
use CFileMan;

/**
 * Bitrix field for image
 */
class BxPicture extends \serviform\FieldBase
{
	/**
	 * @var array
	 */
	public $options = array();


	/**
	 * @return string
	 */
	public function getInput()
	{
		return $this->newOne();
	}

	protected function newOne()
	{
		$defaultOptions = array(
			'description' => true,
			'upload' => true,
			'allowUpload' => 'I',
			'medialib' => true,
			'fileDialog' => true,
			'cloud' => true,
			'delete' => true,
			'maxCount' => 1
		);
		$options = array_merge($defaultOptions, $this->options);
		$options['name'] = $this->getNameChainString();
		$options['id'] = $this->getNameChainString();
		$inst = \Bitrix\Main\UI\FileInput::createInstance($options);
		return $inst->show(intval($this->getValue()));
	}

	protected function oldOne()
	{
		$defaultOptions = array(
			'IMAGE' => 'Y',
			'PATH' => 'Y',
			'FILE_SIZE' => 'Y',
			'DIMENSIONS' => 'Y',
			'IMAGE_POPUP' => 'Y',
			'MAX_SIZE' => array(
				'W' => \COption::GetOptionString('iblock', 'detail_image_size'),
				'H' => \COption::GetOptionString('iblock', 'detail_image_size'),
			),
		);
		$options = array_merge($defaultOptions, $this->options);
		$res = \CFileInput::Show(
			$this->getNameChainString(),
			$this->getValue(), 
			$options,
			array(
				'upload' => true,
				'medialib' => true,
				'file_dialog' => true,
				'cloud' => true,
				'del' => true,
				'description' => true,
			)
		);
		return $res;
	}
}