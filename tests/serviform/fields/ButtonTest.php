<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\Field;
use marvin255\serviform\helpers\FactoryFields;

class ButtonTest extends Field
{
    public function getInputProvider()
    {
        $parent = parent::getInputProvider();

        return array_merge($parent, [
            'simple field' => [
                [
                    'name' => 'test',
                    'label' => 'label',
                ],
                '<button name="test">label</button>',
            ],
            'field with attributes' => [
                [
                    'attributes' => [
                        'type' => 'submit',
                        'class' => 'test',
                        'data-param' => 1,
                    ],
                    'name' => 'test',
                ],
                '<button type="submit" class="test" data-param="1" name="test"></button>',
            ],
            'xss in label' => [
                [
                    'name' => 'test',
                    'label' => '" onclick="alert(\'xss\')" data-param="',
                    'allowHtmlContent' => false,
                ],
                '<button name="test">&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;</button>',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'type' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'name' => 'test',
                ],
                '<button type="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" name="test"></button>',
            ],
            'allow html in label' => [
                [
                    'name' => 'test',
                    'label' => '" onclick="alert(\'xss\')" data-param="',
                    'allowHtmlContent' => true,
                ],
                '<button name="test">" onclick="alert(\'xss\')" data-param="</button>',
            ],
            'xss in value' => [
                [
                    'name' => 'test',
                    'value' => '" onclick="alert(\'xss\')" data-param="',
                    'allowHtmlContent' => false,
                ],
                '<button name="test" value="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;"></button>',
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
        $type = '\\marvin255\\serviform\\fields\\Button';

        return FactoryFields::initElement($type, $options);
    }
}
