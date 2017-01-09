<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\Field;
use marvin255\serviform\helpers\FactoryFields;

class MultipleTest extends Field
{
    public function testSetValue()
    {
        $field = $this->getField();
        $field->setMultiplier(['type' => 'input']);
        $field->setMin(null);
        $field->setMax(null);

        $this->assertSame($field, $field->setValue(['test3' => 3, 1 => '2']));
        $this->assertSame(['test3' => 3, 1 => '2'], $field->getValue());
    }

    /*
    public function testAddError()
    {
        $field = $this->getField();
        $values = [
            'test1' => ['error'],
        ];
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->method('getErrors')->will($this->returnValue([$values['test1'][0]]));
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3->method('getErrors')->will($this->returnValue(null));
        $field->setElements(['test1' => $field2, 'test2' => $field3]);
        $this->assertSame($values, $field->getErrors());
    }

    public function testClearErrors()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->expects($this->once())->method('clearErrors');
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3->expects($this->once())->method('clearErrors');
        $field->setElements(['test1' => $field2, 'test2' => $field3]);
        $this->assertSame($field, $field->clearErrors());
    }
*/
    public function testSetElements()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($field2);
        $field->setMultiplier(['type' => $class]);
        $field->setMin(null);
        $field->setMax(null);
        $elements = [
            'test1' => ['type' => get_class($field2)],
            'test2' => $field2,
        ];
        $this->assertSame($field, $field->setElements($elements));
        $this->assertInstanceOf($class, $field->getElement('test1'));
        $this->assertInstanceOf($class, $field->getElement('test2'));
    }

    public function testGetElementsWithEmptyValue()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($field2);
        $field->setMultiplier(['type' => $class]);
        $field->setMin(3);
        $field->setMax(null);
        $elements = $field->getElements();
        $this->assertCount(3, $elements);
        foreach ($elements as $element) {
            $this->assertInstanceOf($class, $element);
        }
    }

    public function testSetElementsClearPreviousData()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($field2);
        $field->setMultiplier(['type' => $class]);
        $field->setMin(null);
        $field->setMax(null);
        $elements = [
            'test1' => ['type' => $class],
            'test2' => $class,
        ];
        $field->setElements($elements);
        $field->setElements([]);
        $elements = $field->getElements();
        $this->assertCount(0, $elements);
    }

    public function testSetElement()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($field2);
        $field->setMultiplier(['type' => $class]);
        $field->setMin(null);
        $field->setMax(null);
        $this->assertSame($field, $field->setElement('test1', null));
        $this->assertSame($field, $field->setElement('test2', ['value' => 'test value']));
        $this->assertInstanceOf($class, $field->getElement('test1'));
        $this->assertInstanceOf($class, $field->getElement('test2'));
    }

    public function testSetElementWithEmptyName()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($field2);
        $field->setMultiplier(['type' => $class]);
        $field->setMin(null);
        $field->setMax(null);
        $this->setExpectedException('\InvalidArgumentException');
        $field->setElement('', $field2);
    }

    public function testUnsetElement()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($field2);
        $field->setMultiplier(['type' => $class]);
        $field->setMin(null);
        $field->setMax(null);
        $field->setElement('test2', null);
        $field->unsetElement('test2');
        $this->assertSame(null, $field->getElement('test2'));
    }

    public function testSetElementWithMaxLimitExceeded()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $class = get_class($field2);
        $field->setMultiplier(['type' => $class]);
        $field->setMin(null);
        $field->setMax(1);
        $this->setExpectedException('\InvalidArgumentException');
        $field->setElement('test1', null);
        $field->setElement('test2', null);
    }

    public function testSetMin()
    {
        $field = $this->getField();

        $this->assertSame($field, $field->setMin('3'));
        $this->assertSame(3, $field->getMin());
    }

    public function testSetMax()
    {
        $field = $this->getField();

        $this->assertSame($field, $field->setMax('1'));
        $this->assertSame(1, $field->getMax());
    }

    public function testSetItemAttributes()
    {
        $field = $this->getField();
        $attributes = ['test1' => 1, 'test2' => '2'];

        $this->assertSame($field, $field->setItemAttributes($attributes));
        $this->assertSame($attributes, $field->getItemAttributes());
    }

    public function testSetMultiplier()
    {
        $field = $this->getField();
        $multi = ['type' => 'input', 'attributes' => ['class' => 'test_class']];

        $this->assertSame($field, $field->setMultiplier($multi));
        $this->assertSame($multi, $field->getMultiplier());
    }

    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function getField(array $options = array())
    {
        $type = '\\marvin255\\serviform\\fields\\Multiple';
        if (empty($options['multiplier'])) {
            $options['multiplier'] = [
                'type' => 'input',
            ];
        }

        return FactoryFields::initElement($type, $options);
    }
}
