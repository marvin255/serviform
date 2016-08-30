<?php

class DefaultValueTest extends \tests\cases\Validator
{
	public function testConfigValue()
	{
		$validator = $this->getValidator();
		$validator->config(['value' => 123]);
		$this->assertEquals(123, $validator->value);
	}


	public function testValidate()
	{
		$validator = $this->getValidator();
		$validator->value = 'test';
		$form = $this->getTestForm();
		$validator->setParent($form);
		$validator->setElements(['input1', 'input2']);

		$form->getElement('input1')->setValue('');
		$form->getElement('input2')->setValue('');
		$validator->validate();
		$this->assertEquals(
			[
				'input1' => 'test',
				'input2' => 'test',
			],
			$form->getValue()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue('');
		$form->getElement('input2')->setValue('');
		$validator->validate(['input2']);
		$this->assertEquals(
			[
				'input1' => '',
				'input2' => 'test',
			],
			$form->getValue()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue('');
		$form->getElement('input2')->setValue('b');
		$validator->validate();
		$this->assertEquals(
			[
				'input1' => 'test',
				'input2' => 'b',
			],
			$form->getValue()
		);
	}


	public function testSkipOnError()
	{
		$validator = $this->getValidator();
		$validator->value = 'test';
		$validator->skipOnError = true;
		$form = $this->getTestForm();
		$validator->setParent($form);
		$validator->setElements(['input1']);

		$form->getElement('input1')->setValue('');
		$form->getElement('input1')->addError('test skip error');
		$form->getElement('input2')->setValue('b');

		$validator->validate();
		$this->assertEquals(
			[
				'input1' => '',
				'input2' => 'b',
			],
			$form->getValue()
		);

		$validator->skipOnError = false;
		$validator->validate();
		$this->assertEquals(
			[
				'input1' => 'test',
				'input2' => 'b',
			],
			$form->getValue()
		);
	}


	public function getValidator()
	{
		return new \serviform\validators\DefaultValue;
	}
}
