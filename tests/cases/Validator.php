<?php

namespace tests\cases;

/**
 * Base class to test form fields.
 */
abstract class Validator extends \PHPUnit_Framework_TestCase
{
    /**
     * Return object for field representation.
     */
    abstract protected function getValidator();

    public function testSetParent()
    {
        $validator = $this->getValidator();

        $field = new \serviform\fields\Form();
        $validator->setParent($field);
        $this->assertEquals($field, $validator->getParent());
    }

    public function testConfigParent()
    {
        $validator = $this->getValidator();
        $field = new \serviform\fields\Form();
        $validator->config(['parent' => $field]);
        $this->assertEquals($field, $validator->getParent());
    }

    public function testSetElements()
    {
        $validator = $this->getValidator();
        $validator->setElements(['test1', 'test2']);
        $this->assertEquals(['test1', 'test2'], $validator->getElements());
    }

    public function testConfigElements()
    {
        $validator = $this->getValidator();
        $validator->config(['elements' => ['test1', 'test2']]);
        $this->assertEquals(['test1', 'test2'], $validator->getElements());
    }

    public function testConfigMessage()
    {
        $validator = $this->getValidator();
        $validator->config(['message' => 'test']);
        $this->assertEquals('test', $validator->message);
    }

    public function testConfigSkipOnError()
    {
        $validator = $this->getValidator();
        $validator->config(['skipOnError' => true]);
        $this->assertEquals(true, $validator->skipOnError);
        $validator->config(['skipOnError' => false]);
        $this->assertEquals(false, $validator->skipOnError);
    }

    public function testConfigSkipOnEmpty()
    {
        $validator = $this->getValidator();
        $validator->config(['skipOnEmpty' => true]);
        $this->assertEquals(true, $validator->skipOnEmpty);
        $validator->config(['skipOnEmpty' => false]);
        $this->assertEquals(false, $validator->skipOnEmpty);
    }

    public function testConfigWhen()
    {
        $when = function ($validator, $element) {
        };
        $validator = $this->getValidator();
        $validator->config(['when' => $when]);
        $this->assertEquals($when, $validator->when);
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testWrongFieldNameException()
    {
        $validator = $this->getValidator();
        $form = $this->getTestForm();
        $validator->setParent($form);
        $validator->setElements(['input3', 'input2']);
        $validator->validate();
    }

    protected function getTestForm()
    {
        $form = new \serviform\fields\Form();
        $form->setElement('input1', new \serviform\fields\Input());
        $form->setElement('input2', new \serviform\fields\Input());

        return $form;
    }
}
