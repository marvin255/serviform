<?php

namespace marvin255\serviform\tests\cases;

/**
 * Base class to test validators that changes element value.
 */
abstract class ValidatorElementValue extends Validator
{
    public function testValidate()
    {
        $data = $this->getValidatorProvider();
        foreach ($data as $message => $value) {
            $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
            $field->method('getValue')->will($this->returnValue($value[0]));
            if ($value[1] === null) {
                $field->expects($this->never())->method('setValue');
            } else {
                $field->expects($this->once())->method('setValue')->with($value[1]);
            }
            $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
                ->setMethods(['getElement'])
                ->getMock();
            $parent->method('getElement')->with('test')->will($this->returnValue($field));
            $validator = $this->getValidator(isset($value[2]) ? $value[2] : [])
                ->setSkipOnError(false)
                ->setSkipOnEmpty(false)
                ->setWhen(null)
                ->setParent($parent)
                ->setElements(['test'])
                ->validate();
        }
    }

    public function testSkipOnError()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->method('getErrors')->will($this->returnValue(['error']));
        $field->expects($this->never())->method('setValue');
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $parent->method('getElement')->with('test')->will($this->returnValue($field));
        $validator = $this->getMockBuilder(get_class($this->getValidator()))
            ->setMethods(['validateElement'])
            ->getMock();
        $validator->setSkipOnError(true)
            ->setSkipOnEmpty(false)
            ->setWhen(null)
            ->setParent($parent)
            ->setElements(['test'])
            ->validate();
    }

    public function testSkipOnEmpty()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->method('getValue')->will($this->returnValue(''));
        $field->expects($this->never())->method('setValue');
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $parent->method('getElement')->with('test')->will($this->returnValue($field));
        $validator = $this->getMockBuilder(get_class($this->getValidator()))
            ->setMethods(['validateElement'])
            ->getMock();
        $validator->setSkipOnError(false)
            ->setSkipOnEmpty(true)
            ->setWhen(null)
            ->setParent($parent)
            ->setElements(['test'])
            ->validate();
    }

    public function testWhen()
    {
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->expects($this->never())->method('setValue');
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')->getMock();
        $parent->method('getElement')->with('test')->will($this->returnValue($field));
        $validator = $this->getMockBuilder(get_class($this->getValidator()))
            ->setMethods(['validateElement'])
            ->getMock();
        $validator->setSkipOnError(false)
            ->setSkipOnEmpty(false)
            ->setWhen(function ($validator, $element) {
                return false;
            })
            ->setParent($parent)
            ->setElements(['test'])
            ->validate();
    }
}
