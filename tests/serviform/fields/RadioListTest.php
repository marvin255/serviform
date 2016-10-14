<?php

class RadioListTest extends \tests\cases\FieldList
{
    public function getInputProvider()
    {
        return [
            'simple field' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'multiple' => false,
                ],
                '<label for="test_v"><input name="test" value="v" type="radio" id="test_v" checked="checked">l</label><label for="test_v1"><input name="test" value="v1" type="radio" id="test_v1">l1</label>',
            ],
            'multiple field' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'multiple' => true,
                ],
                '<label for="test___v"><input name="test[]" value="v" type="checkbox" id="test___v" checked="checked">l</label><label for="test___v1"><input name="test[]" value="v1" type="checkbox" id="test___v1">l1</label>',
            ],
            'xss in value' => [
                [
                    'name' => 'test',
                    'list' => ['v' => '" onclick="alert(\'xss\')" data-param="', 'v1' => 'l1'],
                    'multiple' => false,
                ],
                '<label for="test_v"><input name="test" value="v" type="radio" id="test_v">&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;</label><label for="test_v1"><input name="test" value="v1" type="radio" id="test_v1">l1</label>',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'class' => '" onclick="alert(\'xss\')" data-param="',
                    ],
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                ],
                '<label for="test_v"><input class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" name="test" value="v" type="radio" id="test_v">l</label><label for="test_v1"><input class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" name="test" value="v1" type="radio" id="test_v1">l1</label>',
            ],
            'label options' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'multiple' => false,
                    'labelOptions' => ['class' => 'label'],
                ],
                '<label class="label" for="test_v"><input name="test" value="v" type="radio" id="test_v" checked="checked">l</label><label class="label" for="test_v1"><input name="test" value="v1" type="radio" id="test_v1">l1</label>',
            ],
            'list items options' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'multiple' => false,
                    'listItemsOptions' => [
                        'v' => ['data-test' => 'test'],
                        'v1' => ['data-test-1' => 'test-1'],
                    ],
                ],
                '<label for="test_v"><input name="test" data-test="test" value="v" type="radio" id="test_v" checked="checked">l</label><label for="test_v1"><input name="test" data-test-1="test-1" value="v1" type="radio" id="test_v1">l1</label>',
            ],
            'template' => [
                [
                    'template' => __DIR__.'/../../files/template.php',
                ],
                "test_template\n"
            ],
        ];
    }

    public function testSetLabelOptions()
    {
        $field = $this->getField();
        $field->setLabelOptions(['k' => 'v']);
        $this->assertEquals(['k' => 'v'], $field->getLabelOptions());
    }

    public function testConfigLabelOptions()
    {
        $field = $this->getField();
        $field->config(['labelOptions' => ['k' => 'v']]);
        $this->assertEquals(['k' => 'v'], $field->getLabelOptions());
    }

    protected function getField()
    {
        return new \serviform\fields\RadioList();
    }
}
