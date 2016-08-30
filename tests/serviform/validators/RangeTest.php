<?php

class RangeTest extends \tests\cases\Validator
{
	public function testConfigNot()
	{
		$validator = $this->getValidator();
		$validator->config(['not' => true]);
		$this->assertEquals(true, $validator->not);
		$validator->config(['not' => false]);
		$this->assertEquals(false, $validator->not);
	}

	public function testConfigStrict()
	{
		$validator = $this->getValidator();
		$validator->config(['strict' => true]);
		$this->assertEquals(true, $validator->strict);
		$validator->config(['strict' => false]);
		$this->assertEquals(false, $validator->strict);
	}

	public function testConfigRange()
	{
		$validator = $this->getValidator();
		$validator->config(['range' => [1,2]]);
		$this->assertEquals([1,2], $validator->range);
	}


	public function testValidate()
	{
		$validator = $this->getValidator();
		$validator->message = 'test validator error';
		$validator->range = [1, 2, 3];
		$form = $this->getTestForm();
		$validator->setParent($form);
		$validator->setElements(['input1', 'input2']);

		$form->getElement('input1')->setValue('a');
		$form->getElement('input2')->setValue('b');
		$validator->validate();
		$this->assertEquals(
			[
				'input1' => ['test validator error'],
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue('a');
		$form->getElement('input2')->setValue('b');
		$validator->validate(['input2']);
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue('b');
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);


		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue('2');
		$validator->strict = true;
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);


		$form->clearErrors();
		$form->getElement('input1')->setValue(5);
		$form->getElement('input2')->setValue('2');
		$validator->not = true;
		$validator->strict = false;
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);
	}


	public function testSkipOnError()
	{
		$validator = $this->getValidator();
		$validator->message = 'test validator error';
		$validator->range = [1, 2, 3];
		$validator->skipOnError = true;
		$form = $this->getTestForm();
		$validator->setParent($form);
		$validator->setElements(['input1']);

		$form->getElement('input1')->setValue('a');
		$form->getElement('input1')->addError('test skip error');

		$validator->validate();
		$this->assertEquals(
			['input1' => ['test skip error']],
			$form->getErrors()
		);

		$validator->skipOnError = false;
		$validator->validate();
		$this->assertEquals(
			['input1' => ['test skip error', 'test validator error']],
			$form->getErrors()
		);
	}


	public function testSkipOnEmpty()
	{
		$validator = $this->getValidator();
		$validator->message = 'test validator error';
		$validator->skipOnEmpty = true;
		$validator->range = [1, 2, 3];
		$form = $this->getTestForm();
		$validator->setParent($form);
		$validator->setElements(['input1']);

		$form->getElement('input1')->setValue('');

		$validator->validate();
		$this->assertEquals(
			[],
			$form->getErrors()
		);

		$validator->skipOnEmpty = false;
		$validator->validate();
		$this->assertEquals(
			['input1' => ['test validator error']],
			$form->getErrors()
		);
	}


	public function getValidator()
	{
		return new \serviform\validators\Range;
	}
}
