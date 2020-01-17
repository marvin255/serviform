<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\helpers\FactoryFields;
use marvin255\serviform\tests\cases\FieldWithList;

class SelectTest extends FieldWithList
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
                '<select name="test"><option value="v" selected="selected">l</option><option value="v1">l1</option></select>',
            ],
            'multiple field' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'isMultiple' => true,
                ],
                '<select multiple="multiple" name="test[]"><option value="v" selected="selected">l</option><option value="v1">l1</option></select>',
            ],
            'prompt' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'value' => 'v',
                    'isMultiple' => false,
                    'prompt' => '-',
                ],
                '<select name="test"><option value="">-</option><option value="v" selected="selected">l</option><option value="v1">l1</option></select>',
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
                '<select data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" name="test"><option value="v">l</option><option value="v1">l1</option></select>',
            ],
            'list items options' => [
                [
                    'name' => 'test',
                    'list' => ['v' => 'l', 'v1' => 'l1'],
                    'listItemsOptions' => [
                        'v' => [
                            'data-param' => '" onclick="alert(\'xss\')" data-param="',
                            'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                        ],
                        'v1' => ['data-test-1' => 'test-1'],
                    ],
                    'isMultiple' => false,
                ],
                '<select name="test"><option data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" value="v">l</option><option data-test-1="test-1" value="v1">l1</option></select>',
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
        $type = '\\marvin255\\serviform\\fields\\Select';

        return FactoryFields::initElement($type, $options);
    }
}
