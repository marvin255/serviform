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


	public function testSetValidatorDescription()
	{
		\serviform\helpers\FactoryValidators::setValidatorDescription(
			'regexp',
			[
				'type' => '\serviform\validators\Regexp',
				'regexp' => '[0-9]+',
			]
		);
		$field = \serviform\helpers\FactoryValidators::init([
			'type' => 'regexp',
		]);
		$this->assertInstanceOf('\serviform\validators\Regexp', $field);
		$this->assertEquals('[0-9]+', $field->regexp);
	}

	public function testGetValidatorDescription()
	{
		\serviform\helpers\FactoryValidators::setValidatorDescription(
			'regexp',
			[
				'type' => '\serviform\validators\Regexp',
				'regexp' => '[0-9]+',
			]
		);
		$this->assertEquals(
			['type' => '\serviform\validators\Regexp', 'regexp' => '[0-9]+'],
			\serviform\helpers\FactoryValidators::getValidatorDescription('regexp')
		);
	}
}
