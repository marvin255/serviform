<?php

class FactoryFieldsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider initProvider
	 */
	public function testInit($options, $expected)
	{
		$field = \serviform\helpers\FactoryFields::init($options);
		$this->assertInstanceOf($expected, $field);
	}

	public function initProvider()
	{
		return [
			'builtin field' => [
				['type' => 'input'],
				'\serviform\fields\Input'
			],
			'force class' => [
				['type' => '\serviform\fields\Input'],
				'\serviform\fields\Input'
			],
		];
	}



	public function testConfigOnInit()
	{
		$field = \serviform\helpers\FactoryFields::init([
			'type' => 'input',
			'name' => 'name',
			'value' => 'value',
		]);
		$this->assertEquals('name', $field->getName());
		$this->assertEquals('value', $field->getValue());
	}
}
