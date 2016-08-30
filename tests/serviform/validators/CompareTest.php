<?php

class CompareTest extends \tests\cases\Validator
{
	public function testConfigOperator()
	{
		$validator = $this->getValidator();
		$validator->config(['operator' => '>=']);
		$this->assertEquals('>=', $validator->operator);
	}

	public function testConfigCompareAttribute()
	{
		$validator = $this->getValidator();
		$validator->config(['compareAttribute' => 'test']);
		$this->assertEquals('test', $validator->compareAttribute);
	}

	public function testConfigCompareValue()
	{
		$validator = $this->getValidator();
		$validator->config(['compareValue' => 'test']);
		$this->assertEquals('test', $validator->compareValue);
	}


	public function testValidate()
	{
		$validator = $this->getValidator();
		$validator->operator = '==';
		$validator->message = 'test validator error';
		$validator->compareValue = 'a';
		$form = $this->getTestForm();
		$validator->setParent($form);
		$validator->setElements(['input1', 'input2']);

		$form->getElement('input1')->setValue('');
		$form->getElement('input2')->setValue('');
		$validator->validate();
		$this->assertEquals(
			[
				'input1' => ['test validator error'],
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue('');
		$form->getElement('input2')->setValue('');
		$validator->validate(['input2']);
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue('b');
		$form->getElement('input2')->setValue('a');
		$validator->operator = '!=';
		$validator->compareValue = 'a';
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue('1');
		$form->getElement('input2')->setValue(1);
		$validator->operator = '!==';
		$validator->compareValue = 1;
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(2);
		$form->getElement('input2')->setValue(1);
		$validator->operator = '>';
		$validator->compareValue = 1;
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue(0);
		$validator->operator = '>=';
		$validator->compareValue = 1;
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(0);
		$form->getElement('input2')->setValue(1);
		$validator->operator = '<';
		$validator->compareValue = 1;
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue(2);
		$validator->operator = '<=';
		$validator->compareValue = 1;
		$validator->validate();
		$this->assertEquals(
			[
				'input2' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue('a');
		$form->getElement('input2')->setValue('a');
		$validator->operator = '!=';
		$validator->compareValue = null;
		$validator->compareAttribute = 'input2';
		$validator->validate(['input1']);
		$this->assertEquals(
			[
				'input1' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue('1');
		$validator->operator = '!==';
		$validator->compareValue = null;
		$validator->compareAttribute = 'input2';
		$validator->validate(['input1']);
		$this->assertEquals(
			[],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue(0);
		$validator->operator = '>';
		$validator->compareValue = null;
		$validator->compareAttribute = 'input2';
		$validator->validate(['input1']);
		$this->assertEquals(
			[],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue(1);
		$validator->operator = '>=';
		$validator->compareValue = null;
		$validator->compareAttribute = 'input2';
		$validator->validate(['input1']);
		$this->assertEquals(
			[],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(2);
		$form->getElement('input2')->setValue(1);
		$validator->operator = '<';
		$validator->compareValue = null;
		$validator->compareAttribute = 'input2';
		$validator->validate(['input1']);
		$this->assertEquals(
			[
				'input1' => ['test validator error'],
			],
			$form->getErrors()
		);

		$form->clearErrors();
		$form->getElement('input1')->setValue(1);
		$form->getElement('input2')->setValue(1);
		$validator->operator = '<=';
		$validator->compareValue = null;
		$validator->compareAttribute = 'input2';
		$validator->validate(['input1']);
		$this->assertEquals(
			[],
			$form->getErrors()
		);
	}


	public function testSkipOnError()
	{
		$validator = $this->getValidator();
		$validator->operator = '==';
		$validator->message = 'test validator error';
		$validator->compareValue = 'a';
		$validator->skipOnError = true;
		$form = $this->getTestForm();
		$validator->setParent($form);
		$validator->setElements(['input1']);

		$form->getElement('input1')->setValue('');
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
		$validator->skipOnEmpty = true;
		$validator->operator = '==';
		$validator->message = 'test validator error';
		$validator->compareValue = 'a';
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
		return new \serviform\validators\Compare;
	}
}
