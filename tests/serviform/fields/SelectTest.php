<?php

class SelectTest extends \tests\cases\FieldList
{
	public function getInputProvider()
	{
		return [
			'simple field' => [
				[
					'name' => 'test',
					'list' => ['v' => 'l', 'v1' => 'l1'],
					'value' => 'v',
					'multiple' => false,
				],
				'<select name="test"><option value="v" selected="selected">l</option><option value="v1">l1</option></select>'
			],
			'multiple field' => [
				[
					'name' => 'test',
					'list' => ['v' => 'l', 'v1' => 'l1'],
					'value' => 'v',
					'multiple' => true,
				],
				'<select multiple="multiple" name="test[]"><option value="v" selected="selected">l</option><option value="v1">l1</option></select>',
			],
			'prompt' => [
				[
					'name' => 'test',
					'list' => ['v' => 'l', 'v1' => 'l1'],
					'value' => 'v',
					'multiple' => false,
					'prompt' => '-',
				],
				'<select name="test"><option value="">-</option><option value="v" selected="selected">l</option><option value="v1">l1</option></select>',
			],
			'xss in attribute' => [
				[
					'attributes' => [
						'class' => '" onclick="alert(\'xss\')" data-param="',
					],
					'name' => 'test',
					'list' => ['v' => 'l', 'v1' => 'l1'],
				],
				'<select class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" name="test"><option value="v">l</option><option value="v1">l1</option></select>'
			],
			'list items options' => [
				[
					'name' => 'test',
					'list' => ['v' => 'l', 'v1' => 'l1'],
					'listItemsOptions' => [
						'v' => ['data-test' => 'test'],
						'v1' => ['data-test-1' => 'test-1'],
					],
				],
				'<select name="test"><option data-test="test" value="v">l</option><option data-test-1="test-1" value="v1">l1</option></select>'
			],
		];
	}



	public function testConfigPrompt()
	{
		$field = $this->getField();
		$field->config(['prompt' => '-']);
		$this->assertEquals('-', $field->prompt);
	}



	protected function getField()
	{
		return new \serviform\fields\Select;
	}
}
