<?php

class FilterTest extends \tests\cases\Validator
{
	public function testConfigFilter()
	{
		$filter = function ($validator, $element) {};
		$validator = $this->getValidator();
		$validator->config(['filter' => $filter]);
		$this->assertEquals($filter, $validator->filter);

		$validator->config(['filter' => 'trim']);
		$this->assertEquals('trim', $validator->filter);
	}


	public function testValidate()
	{
		$validator = $this->getValidator();
		$validator->message = 'test validator error';
		$validator->filter = function ($validator, $element) {
			return false;
		};
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
		$validator->filter = 'trim';
		$form->getElement('input1')->setValue(' test ');
		$validator->validate();
		$this->assertEquals(
			'test',
			$form->getElement('input1')->getValue()
		);
	}


	public function testSkipOnError()
	{
		$validator = $this->getValidator();
		$validator->message = 'test validator error';
		$validator->filter = function ($validator, $element) {
			return false;
		};
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
		$validator->message = 'test validator error';
		$validator->skipOnEmpty = true;
		$validator->filter = function ($validator, $element) {
			return false;
		};
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
		return new \serviform\validators\Filter;
	}
}
