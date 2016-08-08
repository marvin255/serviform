<?php

class FactoryValidatorsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider initProvider
	 */
	public function testInit($options, $expected)
	{
		$field = \serviform\helpers\FactoryValidators::init($options);
		$this->assertInstanceOf($expected, $field);
	}

	public function initProvider()
	{
		return [
			'builtin field' => [
				['type' => 'compare'],
				'\serviform\validators\Compare'
			],
			'force class' => [
				['type' => '\serviform\validators\Compare'],
				'\serviform\validators\Compare'
			],
		];
	}



	public function testConfigOnInit()
	{
		$field = \serviform\helpers\FactoryValidators::init([
			'type' => 'compare',
			'operator' => '>=',
		]);
		$this->assertEquals('>=', $field->operator);
	}
}
