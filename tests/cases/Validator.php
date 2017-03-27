<?php

namespace marvin255\serviform\tests\cases;

/**
 * Base class to test form validators.
 */
abstract class Validator extends \PHPUnit_Framework_TestCase
{
    /**
     * Return object for field representation.
     */
    abstract protected function getValidator(array $options = array());

    /**
     * Return array values to test validate
     */
    abstract protected function getValidatorProvider();

    public function testValidate()
    {
        $data = $this->getValidatorProvider();
        foreach ($data as $message => $value) {
            $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
            $field->method('getValue')->will($this->returnValue($value[0]));
            $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
                ->setMethods(['getElement'])
                ->getMock();
            $parent->method('getElement')->with('test')->will($this->returnValue($field));
            $validator = $this->getValidator(isset($value[2]) ? $value[2] : [])
                ->setSkipOnError(false)
                ->setSkipOnEmpty(false)
                ->setWhen(null)
                ->setParent($parent)
                ->setElements(['test']);
            $this->assertSame($value[1], $validator->validate(), $message);
        }

        foreach ($data as $message => $value) {
            $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
            $field->method('getValue')->will($this->returnValue([$value[0], $value[0]]));
            $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
                ->setMethods(['getElement'])
                ->getMock();
            $parent->method('getElement')->with('test')->will($this->returnValue($field));
            $validator = $this->getValidator(isset($value[2]) ? $value[2] : [])
                ->setSkipOnError(false)
                ->setSkipOnEmpty(false)
                ->setArrayValue(true)
                ->setWhen(null)
                ->setParent($parent)
                ->setElements(['test']);
            $this->assertSame($value[1], $validator->validate(), $message);
        }
    }

    public function testValidateWithWrongFieldName()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
            ->setMethods(['getElement'])
            ->getMock();
        $parent->method('getElement')->with('test')->will($this->returnValue(null));
        $validator = $this->getValidator()
            ->setSkipOnError(false)
            ->setSkipOnEmpty(false)
            ->setWhen(null)
            ->setParent($parent)
            ->setElements(['test']);
        $this->setExpectedException('\InvalidArgumentException');
        $validator->validate();
    }

    public function testValidateWithSelectedFieldsOnly()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
            ->setMethods(['getElement'])
            ->getMock();
        $parent->expects($this->once())
            ->method('getElement')
            ->with('test1')
            ->will($this->returnValue($field));
        $validator = $this->getValidator()
            ->setSkipOnError(false)
            ->setSkipOnEmpty(false)
            ->setWhen(null)
            ->setParent($parent)
            ->setElements(['test', 'test1']);
        $validator->validate(['test1']);
    }

    public function testValidateWithSetArrayValueAndNotArrayValue()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(1));
        $field->method('getName')
            ->will($this->returnValue('test_name'));
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
            ->setMethods(['getElement'])
            ->getMock();
        $parent->expects($this->once())
            ->method('getElement')
            ->with('test1')
            ->will($this->returnValue($field));
        $validator = $this->getValidator()
            ->setSkipOnError(false)
            ->setSkipOnEmpty(false)
            ->setWhen(null)
            ->setArrayValue(true)
            ->setParent($parent)
            ->setElements(['test1']);
        $this->setExpectedException(
            '\InvalidArgumentException',
            'Value must ba an array for element: test_name'
        );
        $validator->validate();
    }

    public function testSetMessage()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setMessage('test message'));
        $this->assertSame('test message', $validator->getMessage());
    }

    public function testSetParent()
    {
        $validator = $this->getValidator();
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $this->assertSame($validator, $validator->setParent($parent));
        $this->assertSame($parent, $validator->getParent());
    }

    public function testSetElements()
    {
        $validator = $this->getValidator();
        $elements = ['test', 'test2'];
        $this->assertSame($validator, $validator->setElements($elements));
        $this->assertSame($elements, $validator->getElements());
    }

    public function testSetSkipOnError()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setSkipOnError(1));
        $this->assertSame(true, $validator->getSkipOnError());
        $this->assertSame($validator, $validator->setSkipOnError(false));
        $this->assertSame(false, $validator->getSkipOnError());
    }

    public function testSkipOnError()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->method('getErrors')->will($this->returnValue(['error']));
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $parent->method('getElement')->with('test')->will($this->returnValue($field));
        $validator = $this->getMockBuilder(get_class($this->getValidator()))
            ->setMethods(['validateElement'])
            ->getMock();
        $validator->method('validateElement')->will($this->returnValue(false));
        $validator->setSkipOnError(true)
            ->setSkipOnEmpty(false)
            ->setWhen(null)
            ->setParent($parent)
            ->setElements(['test']);
        $this->assertSame(true, $validator->validate());
    }

    public function testSetSkipOnEmpty()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setSkipOnEmpty(1));
        $this->assertSame(true, $validator->getSkipOnEmpty());
        $this->assertSame($validator, $validator->setSkipOnEmpty(false));
        $this->assertSame(false, $validator->getSkipOnEmpty());
    }

    public function testSkipOnEmpty()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->method('getValue')->will($this->returnValue(''));
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $parent->method('getElement')->with('test')->will($this->returnValue($field));
        $validator = $this->getMockBuilder(get_class($this->getValidator()))
            ->setMethods(['validateElement'])
            ->getMock();
        $validator->method('validateElement')->will($this->returnValue(false));
        $validator->setSkipOnError(false)
            ->setSkipOnEmpty(true)
            ->setWhen(null)
            ->setParent($parent)
            ->setElements(['test']);
        $this->assertSame(true, $validator->validate());
    }

    public function testSetWhen()
    {
        $validator = $this->getValidator();
        $when = function ($el) {
        };
        $this->assertSame($validator, $validator->setWhen($when));
        $this->assertSame($when, $validator->getWhen());
    }

    public function testSetWhenWithWrongType()
    {
        $validator = $this->getValidator();
        $this->setExpectedException('\InvalidArgumentException');
        $validator->setWhen('test');
    }

    public function testWhen()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $parent->method('getElement')->with('test')->will($this->returnValue($field));
        $validator = $this->getMockBuilder(get_class($this->getValidator()))
            ->setMethods(['validateElement'])
            ->getMock();
        $validator->method('validateElement')->will($this->returnValue(false));
        $validator->setSkipOnError(false)
            ->setSkipOnEmpty(false)
            ->setWhen(function ($validator, $element) {
                return false;
            })
            ->setParent($parent)
            ->setElements(['test']);
        $this->assertSame(true, $validator->validate());
    }

    public function testSetArrayValue()
    {
        $validator = $this->getValidator();
        $arrayValue = true;
        $this->assertSame($validator, $validator->setArrayValue($arrayValue));
        $this->assertSame($arrayValue, $validator->getArrayValue());
    }
}
