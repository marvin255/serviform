<?php

namespace tests\cases;

/**
 * Base class to test form fields
 */
abstract class Field extends \PHPUnit_Framework_TestCase
{
    /**
     * Return object for field representation
     */
    abstract protected function getField();


    /**
     * Test input rendering
     * @dataProvider getInputProvider
     */
    public function testGetInput($options, $expected)
    {
        $field = $this->getField();
        $field->config($options);
        $this->assertEquals($expected, $field->getInput());
    }


    /**
     * Test configuration function
     */
    public function testConfigValue()
    {
        $field = $this->getField();
        $field->config(['value' => 'test']);
        $this->assertEquals('test', $field->getValue());
    }

    public function testConfigAttributes()
    {
        $field = $this->getField();
        $field->config(['attributes' => ['data-1' => 1, 'data-2' => 2]]);
        $this->assertEquals(['data-1' => 1, 'data-2' => 2], $field->getAttributes());
        $this->assertEquals(1, $field->getAttribute('data-1'));
        $this->assertEquals(2, $field->getAttribute('data-2'));
        $this->assertEquals(null, $field->getAttribute('data-3'));
    }

    public function testConfigLabel()
    {
        $field = $this->getField();
        $field->config(['label' => 'test']);
        $this->assertEquals('test', $field->getLabel());
    }


    /**
     * Test error function
     */
    public function testAddError()
    {
        $field = $this->getField();
        $field->addError('test');
        $this->assertEquals(['test'], $field->getErrors());
    }

    public function testClearErrors()
    {
        $field = $this->getField();
        $field->addError('test');
        $field->clearErrors();
        $this->assertEquals([], $field->getErrors());
    }
}
