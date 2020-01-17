<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\helpers\FactoryFields;
use marvin255\serviform\tests\cases\FieldWithValidators;

class FormTest extends FieldWithValidators
{
    public function testLoadData()
    {
        $value = [
            'test' => [
                'test1' => ['test2' => 'test_string'],
            ],
        ];
        $field3 = $this->getMockBuilder('\\marvin255\\serviform\\interfaces\\Field')->getMock();
        $field3->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($value['test']['test1']['test2']));
        $field3->expects($this->once())
            ->method('setValue')
            ->with('test_string');
        $field = $this->getField()->setName('test')->setElement(
            'test1',
            $this->getField()->setElement('test2', $field3)
        );
        $this->assertSame(true, $field->loadData($value));
        $this->assertSame($value['test'], $field->getValue());
        $this->assertSame(false, $field->loadData(['test1' => ['test2' => 'test_string']]));
    }

    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function getField(array $options = [])
    {
        $type = '\\marvin255\\serviform\\fields\\Form';

        return FactoryFields::initElement($type, $options);
    }
}
