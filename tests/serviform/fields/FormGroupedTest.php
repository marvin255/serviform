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

        try {
            $field->setGroups([
                ['label' => 'test', 'elements' => ['unexisted_child']],
            ]);
        } catch (\Exception $e) {
            $this->assertInstanceOf('\InvalidArgumentException', $e);
        }

        try {
            $field->setGroups([
                ['label' => 'test'],
            ]);
        } catch (\Exception $e) {
            $this->assertInstanceOf('\InvalidArgumentException', $e);
        }
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
