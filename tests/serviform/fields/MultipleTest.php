<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\Field;
use marvin255\serviform\helpers\FactoryFields;

class MultipleTest extends Field
{
    public function getInputProvider()
    {
        $parent = parent::getInputProvider();

        return array_merge($parent, [
            'simple field' => [
                [
                    'value' => [0 => 'test', 1 => 'test'],
                ],
                '<div><div>test field</div><div>test field</div></div>',
            ],
            'field with xss in attribute' => [
                [
                    'attributes' => [
                        'type' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'value' => [0 => 'test', 1 => 'test'],
                ],
                '<div type="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss"><div>test field</div><div>test field</div></div>',
            ],
            'field with xss in item attribute' => [
                [
                    'itemAttributes' => [
                        'type' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'value' => [0 => 'test'],
                ],
                '<div><div type="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss">test field</div></div>',
            ],
            'field with min setted' => [
                [
                    'min' => 3,
                ],
                '<div><div>test field</div><div>test field</div><div>test field</div></div>',
            ],
        ]);
    }

    public function testSetElements()
    {
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field = $this->getField()->setMin(null)->setMax(null)->setMultiplier(['type' => get_class($field2)]);

        $this->assertSame(
            $field,
            $field->setElements(['test' => [], 1 => $field2, 5 => $field3])
        );
        $elements = $field->getElements();
        $this->assertCount(3, $elements);
        $this->assertInstanceOf(
            '\\marvin255\\serviform\\interfaces\\Field',
            $elements[0]
        );
        $this->assertSame($field2, $elements[1]);
        $this->assertSame($field3, $elements[2]);
    }

    public function testSetElementsClearPreviousData()
    {
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field = $this->getField()->setMin(null)->setMax(null)->setMultiplier(['type' => get_class($field2)]);

        $field->setElements([0 => [], 1 => []]);
        $field->setElements([0 => $field2]);
        $this->assertSame([0 => $field2], $field->getElements());
    }

    public function testGetElementsWithNoElementsAndSettedMinimum()
    {
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field = $this->getField()->setMin(2)->setMax(null)->setMultiplier(['type' => get_class($field2)]);

        $elements = $field->getElements();
        $this->assertCount(2, $elements);
        $this->assertInstanceOf(get_class($field2), $elements[0]);
        $this->assertInstanceOf(get_class($field2), $elements[1]);
    }

    public function testSetElement()
    {
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field = $this->getField()->setMin(null)->setMax(null)->setMultiplier(['type' => get_class($field2)]);

        $this->assertSame($field, $field->setElement('test2', []));
        $this->assertSame($field, $field->setElement(1, $field2));
        $this->assertInstanceOf(get_class($field2), $field->getElement(0));
        $this->assertSame($field2, $field->getElement(1));
    }

    public function testSetElementWithMaximumExceeded()
    {
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field = $this->getField()->setMin(null)->setMax(1)->setMultiplier(['type' => get_class($field2)]);
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertSame($field, $field->setElement(0, []));
        $this->assertSame($field, $field->setElement(1, []));
    }

    public function testSetElementWithWrongElementInstance()
    {
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field = $this->getField()->setMin(2)->setMax(null)->setMultiplier(['type' => get_class($field2)]);
        $this->setExpectedException('\InvalidArgumentException');
        $field->setElement('test', $this);
    }

    public function testSetValue()
    {
        $field = $this->getField()->setMin(null)->setMax(null);
        $value = [0 => 1, 1 => 'test'];

        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->method('getValue')->will($this->returnValue($value[0]));
        $field->setElement(0, $field2);

        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3->method('getValue')->will($this->returnValue($value[1]));
        $field->setElement(1, $field3);

        $this->assertSame($field, $field->setValue($value));
        $this->assertSame($value, $field->getValue());
    }

    public function testSetValueToUnsettedElement()
    {
        $field = $this->getMockBuilder(get_class($this->getField()))
            ->setMethods(['createElement'])
            ->getMock();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->method('getValue')->will($this->returnValue('test'));
        $field->expects($this->once())->method('createElement')->will($this->returnValue($field2));

        $field->setValue([0 => 'test']);
        $this->assertSame([0 => 'test'], $field->getValue());
    }

    public function testAddError()
    {
        $field = $this->getField()->setMin(null)->setMax(null);
        $value = [0 => ['test 0 error'], 1 => ['test 1 error']];

        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->method('getErrors')->will($this->returnValue($value[0]));
        $field->setElement(0, $field2);

        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3->method('getErrors')->will($this->returnValue($value[1]));
        $field->setElement(1, $field3);

        $this->assertSame($value, $field->getErrors());
    }

    public function testClearErrors()
    {
        $field = $this->getField()->setMin(null)->setMax(null);
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->expects($this->once())->method('clearErrors');
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3->expects($this->once())->method('clearErrors');
        $field->setElements([0 => $field2, 1 => $field3]);
        $this->assertSame($field, $field->clearErrors());
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

    public function testJsonSerialize()
    {
        $field = $this->getField();
        $field->setAttribute('data-test', 'test');
        $field->setName('name');
        $field->addError('test');
        $field->setValue('value');
        $field->setLabel('label');
        $field->setMin(1);
        $field->setMax(2);
        $field->setMultiplier(['test' => 'multiplier']);
        $field->setItemAttributes(['test' => 'attribute']);
        $class = get_class($field);
        $class = explode('\\', $class);
        $class = end($class);

        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->expects($this->once())
            ->method('jsonSerialize')
            ->will($this->returnValue(['test' => 'test']));

        $field->setElements([0 => $field2]);

        $toTest = json_encode([
            'type' => strtolower($class),
            'attributes' => $field->getAttributes(),
            'name' => $field->getName(),
            'fullName' => $field->getFullName(),
            'errors' => $field->getErrors(),
            'value' => $field->getValue(),
            'label' => $field->getLabel(),
            'elements' => [0 => ['test' => 'test']],
            'min' => $field->getMin(),
            'max' => $field->getMax(),
            'itemAttributes' => $field->getItemAttributes(),
        ]);
        $this->assertSame($toTest, json_encode($field));
    }

    public function testValidate()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $field2->expects($this->once())->method('validate')->will($this->returnValue(true));
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $field3->expects($this->once())->method('validate')->will($this->returnValue(false));
        $field->setElements([0 => $field2, 1 => $field3]);

        $this->assertSame(false, $field->validate());
    }

    public function testGetInput()
    {
        $data = $this->getInputProvider();
        foreach ($data as $message => $value) {
            $field = $this->getMockBuilder(get_class($this->getField()))
                ->setMethods(['createElement'])
                ->getMock();
            $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
            $field2->method('getInput')->will($this->returnValue('test field'));
            $field->expects($this->any())->method('createElement')->will($this->returnValue($field2));
            foreach ($value[0] as $optionName => $optionValue) {
                $methodName = 'set' . ucfirst($optionName);
                if (!method_exists($field, $methodName)) {
                    continue;
                }
                $field->$methodName($optionValue);
            }
            $this->assertEquals($value[1], $field->getInput(), $message);
        }
    }

    public function testToString()
    {
        $data = $this->getInputProvider();
        foreach ($data as $message => $value) {
            $field = $this->getMockBuilder(get_class($this->getField()))
                ->setMethods(['createElement'])
                ->getMock();
            $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
            $field2->method('getInput')->will($this->returnValue('test field'));
            $field->expects($this->any())->method('createElement')->will($this->returnValue($field2));
            foreach ($value[0] as $optionName => $optionValue) {
                $methodName = 'set' . ucfirst($optionName);
                if (!method_exists($field, $methodName)) {
                    continue;
                }
                $field->$methodName($optionValue);
            }
            $this->assertEquals($value[1], (string) $field, $message);
        }
    }

    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function getField(array $options = [])
    {
        $type = '\\marvin255\\serviform\\fields\\Multiple';

        return FactoryFields::initElement($type, $options);
    }
}
