<?php

class FormTest extends \tests\cases\Field
{
	/**
	 * @dataProvider getInputProvider
	 */
	public function testGetInput($options, $expected)
	{
		$field = $this->getField();
		$field->config($options);
		$this->assertEquals($expected, $field->getBeginTag());
		$this->assertEquals('</form>', $field->getEndTag());
	}

	public function getInputProvider()
	{
		return [
			'simple field' => [
				[
					'attributes' => [
						'action' => '/test/'
					],
				],
				'<form action="/test/">'
			],
			'xss in attribute' => [
				[
					'attributes' => [
						'action' => '" onclick="" param="'
					],
				],
				'<form action="&quot; onclick=&quot;&quot; param=&quot;">'
			],
		];
	}



	public function testLoadData()
	{
		$field = $this->getField();
		$field->setName('form');
		$field->loadData(['form' => [
			'child' => 'test',
			'child3' => 'test3',
		]]);
		$this->assertEquals(['child' => 'test', 'child2' => null], $field->getValue());
	}



	public function testGetElement()
	{
		$field = $this->getField();
		$this->assertInstanceOf('\serviform\IElement', $field->getElement('child'));
		$this->assertEquals($field, $field->getElement('child')->getParent());
		$this->assertInstanceOf('\serviform\IElement', $field->getElement('child2'));
		$this->assertEquals($field, $field->getElement('child2')->getParent());
		$this->assertEquals(null, $field->getElement('child3'));
	}

	public function testGetElements()
	{
		$field = $this->getField();
		$elements = $field->getElements();
		$this->assertEquals(2, count($elements));
		foreach ($elements as $element) {
			$this->assertInstanceOf('\serviform\IElement', $element);
			$this->assertEquals($field, $element->getParent());
		}
	}



	public function testAddError()
	{
		$field = $this->getField();
		$field->addError('test');
		$this->assertEquals([], $field->getErrors());
	}

	public function testGetErrors()
	{
		$field = $this->getField();
		$field->getElement('child')->addError('test 1');
		$field->getElement('child2')->addError('test 2');
		$this->assertEquals(
			[
				'child' => ['test 1'],
				'child2' => ['test 2'],
			],
			$field->getErrors()
		);
	}

	public function testClearErrors()
	{
		$field = $this->getField();
		$field->getElement('child')->addError('test 1');
		$field->getElement('child2')->addError('test 2');
		$field->clearErrors();
		$this->assertEquals([], $field->getErrors());
	}

	public function testSetValue()
	{
		$field = $this->getField();
		$field->setValue(['child' => 'test', 'child3' => 'test1']);
		$this->assertEquals(['child' => 'test', 'child2' => null], $field->getValue());
	}



	public function testConfigValue()
	{
		$field = $this->getField();
		$field->config(['value' => ['child' => 'test', 1 => 'test1']]);
		$this->assertEquals(['child' => 'test', 'child2' => null], $field->getValue());
	}



	protected function getField()
	{
		$field = new \serviform\fields\Form;
		$field->config([
			'elements' => [
				'child' => [
					'type' => 'input',
					'attributes' => [
						'class' => 'child'
					],
				],
				'child2' => [
					'type' => 'input',
					'attributes' => [
						'class' => 'child'
					],
				],
			]
		]);
		return $field;
	}
}
