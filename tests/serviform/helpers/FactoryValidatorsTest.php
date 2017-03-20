<?php

namespace marvin255\serviform\tests\serviform\helpers;

use PHPUnit_Framework_TestCase;
use marvin255\serviform\helpers\FactoryValidators;

class FactoryValidatorsTest extends PHPUnit_Framework_TestCase
{
    public function testInitElementWithType()
    {
        $model = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $class = get_class($model);

        $description = [
            'type' => $class,
            'param1' => 1,
            'param2' => '2',
        ];
        FactoryValidators::setDescription('test', $description);

        $this->assertInstanceOf($class, FactoryValidators::initElement('test'));
    }

    public function testInitElementWithClassName()
    {
        $model = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $class = get_class($model);
        $this->assertInstanceOf($class, FactoryValidators::initElement($class));
    }

    public function testInitElementWithWrongClassName()
    {
        $this->setExpectedException('\InvalidArgumentException');
        FactoryValidators::initElement(get_class($this));
    }

    public function testGetDescription()
    {
        $model = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $class = get_class($model);

        $this->assertSame(null, FactoryValidators::getDescription(mt_rand().'_'.time()));

        $description = [
            'type' => $class,
            'param1' => 1,
            'param2' => '2',
            'param3' => 3,
        ];
        FactoryValidators::setDescription('test', $description);
        $this->assertSame($description, FactoryValidators::getDescription('test'));

        $description = [
            'type' => $class,
            'param1' => 3,
            'param2' => '4',
        ];
        FactoryValidators::setDescription('test', $description);
        $this->assertSame($description, FactoryValidators::getDescription('test'));
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
        FactoryValidators::setDescription('test', $description);
        $this->assertSame($description, FactoryValidators::getDescription('test'));

        $description1 = [
            'type' => $class,
            'param1' => 3,
            'param2' => '4',
        ];
        FactoryValidators::setDescription('test', $description1, true);
        $this->assertSame(
            array_merge($description, $description1),
            FactoryValidators::getDescription('test')
        );
    }

    public function testSetDescriptionWithEmptyTypeParam()
    {
        $this->setExpectedException('\InvalidArgumentException');
        FactoryValidators::setDescription('test', []);
    }

    public function testSetDescriptionWithWrongTypeParam()
    {
        $this->setExpectedException('\InvalidArgumentException');
        FactoryValidators::setDescription('test', ['type' => 'test']);
    }
}
