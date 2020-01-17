<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\helpers\FactoryFields;
use marvin255\serviform\tests\cases\FieldWithList;

class RadioListTest extends FieldWithList
{
    public function getInputProvider()
    {
        $parent = parent::getInputProvider();

        return array_merge($parent, [
            'simple field' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'isMultiple' => false,
                ],
                '<div><label for="test-v"><input name="test" value="v" type="radio" id="test-v" checked="checked">l</label><label for="test-v1"><input name="test" value="v1" type="radio" id="test-v1">l1</label></div>',
            ],
            'multiple field' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'isMultiple' => true,
                ],
                '<div><label for="test---v"><input name="test[]" value="v" type="checkbox" id="test---v" checked="checked">l</label><label for="test---v1"><input name="test[]" value="v1" type="checkbox" id="test---v1">l1</label></div>',
            ],
            'xss in value' => [
                [
                    'name' => 'test',
                    'list' => ['v' => '" onclick="alert(\'xss\')" data-param="', 'v1' => 'l1'],
                    'isMultiple' => false,
                ],
                '<div><label for="test-v"><input name="test" value="v" type="radio" id="test-v">&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;</label><label for="test-v1"><input name="test" value="v1" type="radio" id="test-v1">l1</label></div>',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'data-param' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'isMultiple' => false,
                ],
                '<div data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss"><label for="test-v"><input name="test" value="v" type="radio" id="test-v">l</label><label for="test-v1"><input name="test" value="v1" type="radio" id="test-v1">l1</label></div>',
            ],
            'label options' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'isMultiple' => false,
                    'labelOptions' => [
                        'data-param' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                ],
                '<div><label data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" for="test-v"><input name="test" value="v" type="radio" id="test-v" checked="checked">l</label><label data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" for="test-v1"><input name="test" value="v1" type="radio" id="test-v1">l1</label></div>',
            ],
            'list items options' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'isMultiple' => false,
                    'listItemsOptions' => [
                        'v' => [
                            'data-param' => '" onclick="alert(\'xss\')" data-param="',
                            'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                        ],
                        'v1' => ['data-test-1' => 'test-1'],
                    ],
                ],
                '<div><label for="test-v"><input name="test" data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" value="v" type="radio" id="test-v" checked="checked">l</label><label for="test-v1"><input name="test" data-test-1="test-1" value="v1" type="radio" id="test-v1">l1</label></div>',
            ],
       ]);
    }

    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function getField(array $options = [])
    {
        $type = '\\marvin255\\serviform\\fields\\RadioList';

        return FactoryFields::initElement($type, $options);
    }
}
