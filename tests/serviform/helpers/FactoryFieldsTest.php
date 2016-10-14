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
                '\serviform\fields\Input',
            ],
            'force class' => [
                ['type' => '\serviform\fields\Input'],
                '\serviform\fields\Input',
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

    /**
     * @expectedException \serviform\Exception
     */
    public function testInitBadType()
    {
        $field = \serviform\helpers\FactoryFields::init([
            'type' => '\Bad\Class',
        ]);
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testInitBadTypeWithoutNamespace()
    {
        $field = \serviform\helpers\FactoryFields::init([
            'type' => 'BadClass',
        ]);
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testInitNoType()
    {
        $field = \serviform\helpers\FactoryFields::init([]);
    }

    public function testSetFieldDescription()
    {
        \serviform\helpers\FactoryFields::setFieldDescription(
            'input',
            [
                'type' => '\serviform\fields\Button',
                'label' => 'test',
            ]
        );
        $field = \serviform\helpers\FactoryFields::init([
            'type' => 'input',
            'name' => 'name',
            'value' => 'value',
        ]);
        $this->assertInstanceOf('\serviform\fields\Button', $field);
        $this->assertEquals('test', $field->getLabel());
    }

    public function testGetFieldDescription()
    {
        \serviform\helpers\FactoryFields::setFieldDescription(
            'input',
            [
                'type' => '\serviform\fields\Button',
                'label' => 'test',
            ]
        );
        $this->assertEquals(
            ['type' => '\serviform\fields\Button', 'label' => 'test'],
            \serviform\helpers\FactoryFields::getFieldDescription('input')
        );
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testSetFieldDescriptionBadType()
    {
        $field = \serviform\helpers\FactoryFields::setFieldDescription('input', [
            'type' => '\Bad\Class',
        ]);
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testSetFieldDescriptionNoType()
    {
        $field = \serviform\helpers\FactoryFields::setFieldDescription('input', []);
    }
}
