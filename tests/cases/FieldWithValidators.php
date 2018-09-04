<?php

namespace marvin255\serviform\tests\cases;

abstract class FieldWithValidators extends FieldWithChildren
{
    public function testSetRules()
    {
        $rules = [[['test'], 'required']];
        $field = $this->getField();
        $this->assertSame($field, $field->setRules($rules));
        $this->assertSame($rules, $field->getRules());
    }

    public function testSetRulesWithBadElements()
    {
        $rules = [['test', 'required']];
        $field = $this->getField();
        $this->setExpectedException('\InvalidArgumentException');
        $field->setRules($rules);
    }

    public function testSetRulesWithBadType()
    {
        $rules = [[['test'], ['required']]];
        $field = $this->getField();
        $this->setExpectedException('\InvalidArgumentException');
        $field->setRules($rules);
    }

    public function testValidate()
    {
        $field = $this->getMockBuilder(get_class($this->getField()))
            ->setMethods(['createValidator'])
            ->getMock();
        $field->setElement(
            'test',
            $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock()
        );
        $field->setRules([
            [['test'], 'required'],
            [['test'], 'email'],
        ]);

        $validator = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $validator->expects($this->once())->method('validate')->will($this->returnValue(true));

        $validator2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $validator2->expects($this->once())->method('validate')->will($this->returnValue(false));

        $field->expects($this->at(0))
            ->method('createValidator')
            ->with('required', ['parent' => $field, 'elements' => ['test']])
            ->will($this->returnValue($validator));

        $field->expects($this->at(1))
            ->method('createValidator')
            ->with('email', ['parent' => $field, 'elements' => ['test']])
            ->will($this->returnValue($validator2));

        $this->assertSame(false, $field->validate());
    }

    public function testValidateRecoursive()
    {
        $field = $this->getMockBuilder(get_class($this->getField()))
            ->setMethods(['createValidator'])
            ->getMock();
        $field2 = $this->getMockBuilder(get_class($this->getField()))
            ->setMethods(['validate'])
            ->getMock();
        $field2->expects($this->once())
            ->method('validate')
            ->with([])
            ->will($this->returnValue(false));
        $field->setElement('test', $field2);

        $this->assertSame(false, $field->validate());
    }

    public function testValidateWithFieldNames()
    {
        $field = $this->getMockBuilder(get_class($this->getField()))
            ->setMethods(['createValidator'])
            ->getMock();
        $field->setElement(
            'test',
            $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock()
        );
        $field->setElement(
            'test1',
            $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock()
        );
        $field->setRules([
            [['test', 'test1'], 'required'],
            [['test'], 'email'],
        ]);

        $validator = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $validator->expects($this->once())
            ->method('validate')
            ->with(['test1'])
            ->will($this->returnValue(true));

        $field->expects($this->once())
            ->method('createValidator')
            ->with('required', ['elements' => ['test', 'test1'], 'parent' => $field])
            ->will($this->returnValue($validator));

        $this->assertSame(true, $field->validate(['test1']));
    }

    public function testValidateWithWrongVaidatorClass()
    {
        $field = $this->getField();
        $field->setRules([
            [['test'], get_class($this)],
        ]);
        $this->setExpectedException('\InvalidArgumentException');
        $field->validate();
    }

    public function testValidateWithSetValidator()
    {
        $field = $this->getMockBuilder(get_class($this->getField()))
            ->setMethods(['createValidator'])
            ->getMock();
        $field->setElement(
            'test',
            $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock()
        );
        $field->setElement(
            'test1',
            $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock()
        );

        $validator = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $validator->method('getElements')->will($this->returnValue(['test1']));
        $validator->expects($this->once())
            ->method('validate')
            ->with(['test1'])
            ->will($this->returnValue(true));
        $field->setValidator('test_validator', $validator);

        $validator2 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $validator2->method('getElements')->will($this->returnValue(['test']));
        $validator2->expects($this->never())->method('validate');
        $field->setValidator('test_validator_2', $validator2);

        $this->assertSame(true, $field->validate(['test1']));
    }

    public function testSetValidator()
    {
        $field = $this->getField();
        $validator = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();

        $field->setValidator('test', $validator);
        $this->assertSame($validator, $field->getValidator('test'));
    }

    public function testSetValidatorWithEmptyName()
    {
        $field = $this->getField();
        $validator = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Validator')->getMock();
        $this->setExpectedException('\InvalidArgumentException');
        $field->setValidator('', $validator);
    }

    public function testSetValidatorWithEmptyType()
    {
        $field = $this->getField();
        $this->setExpectedException('\InvalidArgumentException');
        $field->setValidator('test', []);
    }

    public function testGetValidatorWithNoRuleException()
    {
        $field = $this->getField();
        $this->setExpectedException('\InvalidArgumentException');
        $field->getValidator('unexisted_validator');
    }
}
