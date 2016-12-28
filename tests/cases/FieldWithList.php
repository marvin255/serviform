<?php

namespace marvin255\serviform\tests\cases;

abstract class FieldWithList extends Field
{
    public function testSetList()
    {
        $field = $this->getField();
        $list = [1, 2, 3];
        $field->setList($list);
        $this->assertSame($list, $field->getList());
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $field->setIsMultiple(false);
        $field->setValue([1, 2, 3]);
        $this->assertSame(1, $field->getValue());
        $field->setValue('test');
        $this->assertSame('test', $field->getValue());
    }

    public function testSetValueMultiple()
    {
        $field = $this->getField();
        $field->setIsMultiple(true);
        $field->setValue([1, 2, 3]);
        $this->assertSame([1, 2, 3], $field->getValue());
        $field->setValue('test');
        $this->assertSame(['test'], $field->getValue());
    }

    public function testGetNameChainString()
    {
        $field = $this->getField();
        $field->setIsMultiple(false);
        $field->setName('child');
        $this->assertSame('child', $field->getNameChainString());

        $parent1 = $this->getField();
        $parent1->setName('parent1');
        $field->setParent($parent1);
        $this->assertSame('parent1[child]', $field->getNameChainString());

        $parent2 = $this->getField();
        $parent2->setName('parent2');
        $parent1->setParent($parent2);
        $this->assertSame('parent2[parent1][child]', $field->getNameChainString());
    }

    public function testGetNameChainStringMultiple()
    {
        $field = $this->getField();
        $field->setIsMultiple(true);
        $field->setName('child');
        $this->assertSame('child[]', $field->getNameChainString());

        $parent1 = $this->getField();
        $parent1->setName('parent1');
        $field->setParent($parent1);
        $this->assertSame('parent1[child][]', $field->getNameChainString());

        $parent2 = $this->getField();
        $parent2->setName('parent2');
        $parent1->setParent($parent2);
        $this->assertSame('parent2[parent1][child][]', $field->getNameChainString());
    }

    public function testJsonSerialize()
    {
        $field = $this->getField();
        $field->setAttribute('data-test', 'test');
        $field->setName('name');
        $field->addError('test');
        $field->setValue('value');
        $field->setLabel('label');
        $field->setList([1, 2, 3]);
        $field->setIsMultiple(true);
        $field->getListItemsOptions([1, 2, 3]);
        $class = get_class($field);
        $class = explode('\\', $class);
        $class = end($class);
        $toTest = json_encode([
            'type' => strtolower($class),
            'attributes' => $field->getAttributes(),
            'name' => $field->getName(),
            'fullName' => $field->getFullName(),
            'errors' => $field->getErrors(),
            'value' => $field->getValue(),
            'label' => $field->getLabel(),
            'list' => $field->getList(),
            'listItemsOptions' => $field->getListItemsOptions(),
            'multiple' => $field->getIsMultiple(),
        ]);
        $this->assertSame($toTest, json_encode($field));
    }
}
