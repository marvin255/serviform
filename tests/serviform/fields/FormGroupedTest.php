<?php

require_once __DIR__.'/FormTest.php';

class FormGroupedTest extends FormTest
{
    /**
     * @return array
     */
    public function testGetGroups()
    {
        $field = $this->getfield();
        $field->setGroups([
            ['label' => 'test', 'elements' => ['child']],
        ]);
        $this->assertEquals(
            [['label' => 'test', 'elements' => ['child' => []]]],
            $field->getGroups()
        );
        $field->setGroups([
            ['label' => 'test', 'elements' => ['child' => ['key' => 'value']]],
        ]);
        $this->assertEquals(
            [['label' => 'test', 'elements' => ['child' => ['key' => 'value']]]],
            $field->getGroups()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistedChildInGroupSetting()
    {
        $field = $this->getfield();
        $field->setGroups([
            ['label' => 'test', 'elements' => ['unexisted_child']],
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistedChildKeyInGroupSetting()
    {
        $field = $this->getfield();
        $field->setGroups([
            ['label' => 'test', 'elements' => ['unexisted_child' => ['key' => 'value']]],
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistedElementsParamInGroupSetting()
    {
        $field = $this->getfield();
        $field->setGroups([
            ['label' => 'test'],
        ]);
    }

    protected function getField()
    {
        $field = new \serviform\fields\FormGrouped();
        $field->config([
            'elements' => [
                'child' => [
                    'type' => 'input',
                    'attributes' => [
                        'class' => 'child',
                    ],
                ],
                'child2' => [
                    'type' => 'input',
                    'attributes' => [
                        'class' => 'child',
                    ],
                ],
            ],
        ]);

        return $field;
    }
}
