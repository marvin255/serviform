<?php

namespace marvin255\serviform\tests\cases;

abstract class FieldWithChildren extends Field
{
    public function testSetElements()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $elements = [
            'test1' => ['type' => get_class($field)],
            'test2' => $field2,
        ];
        $this->assertSame($field, $field->setElements($elements));
        $elements = $field->getElements();
        $this->assertCount(2, $elements);
        $this->assertArrayHasKey('test1', $elements);
        $this->assertArrayHasKey('test2', $elements);
        $this->assertInstanceOf(
            '\\marvin255\\serviform\\interfaces\\Field',
            $elements['test1']
        );
        $this->assertInstanceOf(
            '\\marvin255\\serviform\\interfaces\\Field',
            $elements['test2']
        );
    }

    public function testSetElementsClearPreviousData()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $elements = [
            'test1' => ['type' => get_class($field)],
            'test2' => $field2,
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
        $field->setElement('test2', ['type' => get_class($field)]);
        $this->assertSame($field, $field->setElement('test1', $field2));
        $this->assertSame($field2, $field->getElement('test1'));
        $this->assertInstanceOf(get_class($field), $field->getElement('test2'));
    }

    public function testSetElementWithPositionParam()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field4 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field->setElement('test2', $field2);
        $field->setElement('test3', $field3);
        $field->setElement('test4', $field4, 1);
        $this->assertSame(
            ['test2' => $field2, 'test4' => $field4, 'test3' => $field3],
            $field->getElements()
        );
    }

    public function testSetElementWithNegativePositionParam()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field4 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field->setElement('test2', $field2);
        $field->setElement('test3', $field3);
        $field->setElement('test4', $field4, -2);
        $this->assertSame(
            ['test4' => $field4, 'test2' => $field2, 'test3' => $field3],
            $field->getElements()
        );
    }

    public function testSetElementWithEmptyName()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $this->setExpectedException('\InvalidArgumentException');
        $field->setElement('', $field2);
    }

    public function testSetElementWithWrongElementInstance()
    {
        $field = $this->getField();
        $this->setExpectedException('\InvalidArgumentException');
        $field->setElement('test', $this);
    }

    public function testSetElementWithWrongElementType()
    {
        $field = $this->getField();
        $this->setExpectedException('\InvalidArgumentException');
        $field->setElement('test', ['type' => get_class($this)]);
    }

    public function testUnsetElement()
    {
        $field = $this->getField();
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field->setElement('test2', ['type' => get_class($field)]);
        $field->unsetElement('test2');
        $this->assertSame(null, $field->getElement('test2'));
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $values = [
            'test1' => 1,
            'test2' => '2',
        ];
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($values['test1']));
        $field2->expects($this->once())
            ->method('setValue')
            ->with($values['test1']);
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($values['test2']));
        $field3->expects($this->once())
            ->method('setValue')
            ->with($values['test2']);

        $field->setElements(['test1' => $field2, 'test2' => $field3]);
        $this->assertSame(
            $field,
            $field->setValue(array_merge($values, ['test3' => 3]))
        );
        $this->assertSame($values, $field->getValue());
    }

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

    public function testJsonSerialize()
    {
        $field = $this->getField();
        $field->setAttribute('data-test', 'test');
        $field->setName('name');
        $field->addError('test');
        $field->setValue('value');
        $field->setLabel('label');
        $class = get_class($field);
        $class = explode('\\', $class);
        $class = end($class);

        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field2->expects($this->once())
            ->method('jsonSerialize')
            ->will($this->returnValue(['test' => 'test']));

        $field->setElements(['test1' => $field2]);

        $toTest = json_encode([
            'type' => strtolower($class),
            'attributes' => $field->getAttributes(),
            'name' => $field->getName(),
            'fullName' => $field->getFullName(),
            'errors' => $field->getErrors(),
            'value' => $field->getValue(),
            'label' => $field->getLabel(),
            'elements' => ['test1' => ['test' => 'test']],
        ]);
        $this->assertSame($toTest, json_encode($field));
    }
}
