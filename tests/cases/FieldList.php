<?php

namespace tests\cases;

/**
 * Base class to test form fields.
 */
abstract class FieldList extends Field
{
    public function testSetList()
    {
        $field = $this->getField();
        $field->setList([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $field->getList());
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $field->multiple = false;
        $field->setValue([1, 2, 3]);
        $this->assertEquals(1, $field->getValue());
        $field->setValue('test');
        $this->assertEquals('test', $field->getValue());
    }

    public function testSetValueMultiple()
    {
        $field = $this->getField();
        $field->multiple = true;
        $field->setValue([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $field->getValue());
        $field->setValue('test');
        $this->assertEquals(['test'], $field->getValue());
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
        $field->multiple = false;
        $this->assertEquals(
            'field2[field1][field]',
            $field->getNameChainString()
        );
        $field->multiple = true;
        $this->assertEquals(
            'field2[field1][field][]',
            $field->getNameChainString()
        );
    }

    public function testConfigList()
    {
        $field = $this->getField();
        $field->config(['list' => [1, 2, 'test']]);
        $this->assertEquals([1, 2, 'test'], $field->getList());
    }

    public function testConfigMultiple()
    {
        $field = $this->getField();
        $field->config(['multiple' => true]);
        $this->assertEquals(true, $field->multiple);
    }

    public function testConfigListItemOptions()
    {
        $field = $this->getField();
        $field->config(['listItemsOptions' => ['test' => 'test']]);
        $this->assertEquals(['test' => 'test'], $field->getListItemsOptions());
    }

    public function testJsonSerialize()
    {
        $field = $this->getField();
        $field->setAttribute('data-test', 'test');
        $field->addError('test');
        $field->setList([1 => 'test', '2_test' => 'test']);
        $errors = $field->getErrors();

        $toTest = json_encode([
            'value' => $field->getValue(),
            'name' => $field->getName(),
            'fullName' => $field->getFullName(),
            'errors' => $errors ? $errors : null,
            'label' => $field->getLabel(),
            'attributes' => $field->getAttributes(),
            'list' => $field->getList(),
            'listItemsOptions' => $field->getListItemsOptions(),
            'multiple' => $field->multiple,
        ]);
        $this->assertEquals($toTest, json_encode($field));
    }
}
