<?php

namespace tests\cases;

/**
 * Base class to test form fields.
 */
abstract class Field extends \PHPUnit_Framework_TestCase
{
    /**
     * Return object for field representation.
     */
    abstract protected function getField();

    /**
     * @dataProvider getInputProvider
     */
    public function testGetInput($options, $expected)
    {
        $field = $this->getField();
        $field->config($options);
        $this->assertEquals($expected, $field->getInput());
    }

    /**
     * @dataProvider getInputProvider
     */
    public function testToString($options, $expected)
    {
        $field = $this->getField();
        $field->config($options);
        $this->assertEquals($expected, (string) $field);
    }

    public function testSetParent()
    {
        $field = $this->getField();
        $field2 = $this->getField();
        $field->setParent($field2);
        $this->assertEquals($field2, $field->getParent());
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $field->setValue('test');
        $this->assertEquals('test', $field->getValue());
    }

    public function testSetAttribute()
    {
        $field = $this->getField();
        $field->setAttribute('data-1', 1);
        $this->assertEquals(1, $field->getAttribute('data-1'));
    }

    public function testGetAttribute()
    {
        $field = $this->getField();
        $field->setAttributes(['data-1' => 1, 'data-2' => 2]);
        $field->setAttribute('data-3', 3);
        $this->assertEquals(1, $field->getAttribute('data-1'));
        $this->assertEquals(2, $field->getAttribute('data-2'));
        $this->assertEquals(3, $field->getAttribute('data-3'));
        $this->assertEquals(null, $field->getAttribute('data-4'));
    }

    public function testGetAttributes()
    {
        $field = $this->getField();
        $field->setAttributes(['data-1' => 1, 'data-2' => 2]);
        $field->setAttribute('data-3', 3);
        $this->assertEquals(
            ['data-1' => 1, 'data-2' => 2, 'data-3' => 3],
            $field->getAttributes()
        );
    }

    public function testAddToAttribute()
    {
        $field = $this->getField();
        $field->setAttributes(['data-1' => 1, 'data-2' => 2]);
        $field->addToAttribute('data-1', ' 2');
        $this->assertEquals(
            ['data-1' => '1 2', 'data-2' => 2],
            $field->getAttributes()
        );
    }

    public function testSetName()
    {
        $field = $this->getField();
        $field->setName('test');
        $this->assertEquals('test', $field->getName());
    }

    public function testGetFullName()
    {
        $field = $this->getField();
        $field->setName('field');
        $field1 = $this->getField();
        $field1->setName('field1');
        $field2 = $this->getField();
        $field2->setName('field2');
        $field1->setParent($field2);
        $field->setParent($field1);
        $this->assertEquals(
            ['field2', 'field1', 'field'],
            $field->getFullName()
        );
    }

    public function testGetNameChainString()
    {
        $field = $this->getField();
        $field->setName('field');
        $field1 = $this->getField();
        $field1->setName('field1');
        $field2 = $this->getField();
        $field2->setName('field2');
        $field1->setParent($field2);
        $field->setParent($field1);
        $this->assertEquals(
            'field2[field1][field]',
            $field->getNameChainString()
        );
    }

    public function testAddError()
    {
        $field = $this->getField();
        $field->addError('test');
        $this->assertEquals(['test'], $field->getErrors());
    }

    public function testGetErrors()
    {
        $field = $this->getField();
        $field->addError('test 1');
        $field->addError('test 2');
        $field->addError('test 3');
        $this->assertEquals(
            ['test 1', 'test 2', 'test 3'],
            $field->getErrors()
        );
    }

    public function testClearErrors()
    {
        $field = $this->getField();
        $field->addError('test');
        $field->clearErrors();
        $this->assertEquals([], $field->getErrors());
    }

    public function testSetLabel()
    {
        $field = $this->getField();
        $field->setLabel('test');
        $this->assertEquals('test', $field->getLabel());
    }

    public function testRenderTemplate()
    {
        $field = $this->getField();
        $field->setTemplate(function ($field) {
            return 'test_callable';
        });
        $this->assertEquals('test_callable', $field->renderTemplate());
        $field->setTemplate(__DIR__.'/../files/template.php');
        $this->assertEquals("test_template\n", $field->renderTemplate());
    }

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
        $this->assertEquals(
            ['data-1' => 1, 'data-2' => 2],
            $field->getAttributes()
        );
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

    public function testConfigName()
    {
        $field = $this->getField();
        $field->config(['name' => 'test']);
        $this->assertEquals('test', $field->getName());
    }

    public function testConfigParent()
    {
        $field = $this->getField();
        $field2 = $this->getField();
        $field->config(['parent' => $field2]);
        $this->assertEquals($field2, $field->getParent());
    }

    public function testConfigTemplate()
    {
        $field = $this->getField();
        $field->config(['template' => 'test']);
        $this->assertEquals('test', $field->getTemplate());
    }

    public function testJsonSerialize()
    {
        $field = $this->getField();
        $field->setAttribute('data-test', 'test');
        $field->addError('test');
        $errors = $field->getErrors();

        $toTest = json_encode([
            'value' => $field->getValue(),
            'name' => $field->getName(),
            'fullName' => $field->getFullName(),
            'errors' => $errors ? $errors : null,
            'label' => $field->getLabel(),
            'attributes' => $field->getAttributes(),
        ]);
        $this->assertEquals($toTest, json_encode($field));
    }
}
