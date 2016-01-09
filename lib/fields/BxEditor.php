<?php

namespace serviform\fields;

use \serviform\helpers\Html;
use CFileMan;

/**
 * Bitrix field with editor class
 */
class BxEditor extends \serviform\FieldBase
{
	/**
	 * @var string
	 */
	public $textType = 'text';
	/**
	 * @var string
	 */
	public $textTypeName = 'text';
	/**
	 * @var string
	 */
	public $iblockId = null;
	/**
	 * @var string
	 */
	public $iblockLID = null;



	/**
	 * @return string
	 */
	public function getInput()
	{
		ob_start();
		ob_implicit_flush(false);
		CFileMan::AddHTMLEditorFrame(
			$this->getNameChainString(),
			$this->getValue(),
			$this->textTypeName,
			$this->textType,
			$this->getAttributes(),
			'N',
			0,
			'',
			'',
			$this->iblockLID,
			true,
			false,
			array(
				'toolbarConfig' => CFileman::GetEditorToolbarConfig('iblock_admin'),
				'saveEditorKey' => $this->iblockId,
				'hideTypeSelector' => false,
			)
		);
		return ob_get_clean();
	}
}