<?php

namespace marvin255\serviform\tests\serviform\helpers;

use PHPUnit_Framework_TestCase;
use marvin255\serviform\helpers\FactoryFields;

class FactoryFieldsTest extends PHPUnit_Framework_TestCase
{
    public function testInitElementWithType()
    {
        $model = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($model);

        $description = [
            'type' => $class,
            'param1' => 1,
            'param2' => '2',
        ];
        FactoryFields::setDescription('test', $description);

        $this->assertInstanceOf($class, FactoryFields::initElement('test'));
    }

    public function testInitElementWithClassName()
    {
        $model = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($model);
        $this->assertInstanceOf($class, FactoryFields::initElement($class));
    }

    public function testInitElementWithWrongClassName()
    {
        $this->setExpectedException('\InvalidArgumentException');
        FactoryFields::initElement(get_class($this));
    }

    public function testGetDescription()
    {
        $model = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($model);

        $this->assertSame(null, FactoryFields::getDescription(mt_rand() . '_' . time()));

        $description = [
            'type' => $class,
            'param1' => 1,
            'param2' => '2',
            'param3' => 3,
        ];
        FactoryFields::setDescription('test', $description);
        $this->assertSame($description, FactoryFields::getDescription('test'));

        $description = [
            'type' => $class,
            'param1' => 3,
            'param2' => '4',
        ];
        FactoryFields::setDescription('test', $description);
        $this->assertSame($description, FactoryFields::getDescription('test'));
    }

    public function testSetDescriptionWithrueMergeOptions()
    {
        $model = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($model);

        $description = [
            'type' => $class,
            'param1' => 1,
            'param2' => '2',
            'param3' => 3,
        ];
        FactoryFields::setDescription('test', $description);
        $this->assertSame($description, FactoryFields::getDescription('test'));

        $description1 = [
            'type' => $class,
            'param1' => 3,
            'param2' => '4',
        ];
        FactoryFields::setDescription('test', $description1, true);
        $this->assertSame(
            array_merge($description, $description1),
            FactoryFields::getDescription('test')
        );
    }

    public function testSetDescriptionWithEmptyTypeParam()
    {
        $this->setExpectedException('\InvalidArgumentException');
        FactoryFields::setDescription('test', []);
    }

    public function testSetDescriptionWithWrongTypeParam()
    {
        $this->setExpectedException('\InvalidArgumentException');
        FactoryFields::setDescription('test', ['type' => 'test']);
    }
}
