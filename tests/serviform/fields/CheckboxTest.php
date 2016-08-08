<?php

class CheckboxTest extends \tests\cases\Field
{
	public function getInputProvider()
	{
		return [
			'simple field' => [
				[
					'name' => 'test',
					'label' => 'label',
					'trueValue' => 'test_true',
					'falseValue' => 'test_false',
				],
				'<input type="hidden" name="test" value="test_false"><input name="test" value="test_true" type="checkbox">'
			],
			'field with attributes' => [
				[
					'attributes' => [
						'class' => 'test',
						'data-param' => 1,
					],
					'name' => 'test',
					'trueValue' => 1,
					'falseValue' => 0,
				],
				'<input type="hidden" name="test" value="0"><input class="test" data-param="1" name="test" value="1" type="checkbox">'
			],
			'xss in value' => [
				[
					'name' => 'test',
					'value' => '" onclick="alert(\'xss\')" data-param="',
					'trueValue' => 1,
					'falseValue' => 0,
				],
				'<input type="hidden" name="test" value="0"><input name="test" value="1" type="checkbox">'
			],
			'xss in attribute' => [
				[
					'attributes' => [
						'class' => '" onclick="alert(\'xss\')" data-param="',
					],
					'name' => 'test',
					'trueValue' => 1,
					'falseValue' => 0,
				],
				'<input type="hidden" name="test" value="0"><input class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" name="test" value="1" type="checkbox">'
			],
		];
	}



	public function testConfigTrueValue()
	{
		$field = $this->getField();
		$field->config(['trueValue' => 1]);
		$this->assertEquals(1, $field->trueValue);
		$field->config(['trueValue' => 'test']);
		$this->assertEquals('test', $field->trueValue);
	}

	public function testConfigFalseValue()
	{
		$field = $this->getField();
		$field->config(['falseValue' => 1]);
		$this->assertEquals(1, $field->falseValue);
		$field->config(['falseValue' => 'test']);
		$this->assertEquals('test', $field->falseValue);
	}



	protected function getField()
	{
		return new \serviform\fields\Checkbox;
	}
}
