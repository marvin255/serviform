<?php

class HtmlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider tagProvider
     */
    public function testTag($tag, $options, $content, $expected)
    {
        $res = \serviform\helpers\Html::tag($tag, $options, $content);
        $this->assertEquals($expected, $res);
    }

    public function tagProvider()
    {
        return [
            'tag with content' => [
                'div',
                null,
                'test',
                '<div>test</div>'
            ],
            'tag without content' => [
                'br',
                null,
                false,
                '<br>'
            ],
            'self closing tag' => [
                'br',
                null,
                true,
                '<br>'
            ],
            'tag with params' => [
                'div',
                ['data-param-1' => 1, 'data-param-2' => 2],
                'test',
                '<div data-param-1="1" data-param-2="2">test</div>'
            ],
        ];
    }


    public function testCloseTag()
    {
        $res = \serviform\helpers\Html::closeTag('div');
        $this->assertEquals('</div>', $res);
    }


    /**
     * @dataProvider toIdProvider
     */
    public function testToId($input, $expected)
    {
        $res = \serviform\helpers\Html::toId($input);
        $this->assertEquals($expected, $res);
    }

    public function toIdProvider()
    {
        return [
            'xss' => [
                '" onclick="alert(\'xss\')" data-param="',
                '__onclick__alert__xss____data-param__'
            ],
            'brackets' => ['[test]', '_test_'],
            'lower case' => ['TEst', 'test'],
        ];
    }


    /**
     * @dataProvider createAttributesProvider
     */
    public function testCreateAttributes($input, $expected)
    {
        $res = \serviform\helpers\Html::createAttributes($input);
        $this->assertEquals($expected, $res);
    }

    public function createAttributesProvider()
    {
        return [
            'xss in key' => [
                ['onclick="alert(\'xss\')" data-param' => 'test'],
                'onclick__alert__xss____data-param="test"'
            ],
            'xss in value' => [
                ['data-param' => '" onclick="alert(\'xss\')" data-param="'],
                'data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;"'
            ],
            'multiple params' => [
                ['data-test-1' => 1, 'data-test-2' => 2],
                'data-test-1="1" data-test-2="2"'
            ],
        ];
    }


    /**
     * @dataProvider clearTextProvider
     */
    public function testClearAttribute($string, $expected)
    {
        $res = \serviform\helpers\Html::clearAttribute($string);
        $this->assertEquals($expected, $res);
    }


    /**
     * @dataProvider clearAttributeKeyProvider
     */
    public function testClearAttributeKey($string, $expected)
    {
        $res = \serviform\helpers\Html::clearAttributeKey($string);
        $this->assertEquals($expected, $res);
    }

    public function clearAttributeKeyProvider()
    {
        return [
            'ampersand' => ['&', '_'],
            'quote' => ["'", '_'],
            'double quote' => ['"', '_'],
            'greater than' => ['>', '_'],
            'lesser than' => ['<', '_'],
            'equal' => ['=', '_'],
        ];
    }


    /**
     * @dataProvider clearTextProvider
     */
    public function testClearText($string, $expected)
    {
        $res = \serviform\helpers\Html::clearText($string);
        $this->assertEquals($expected, $res);
    }

    public function clearTextProvider()
    {
        return [
            'ampersand' => ['&', '&amp;'],
            'quote' => ["'", '&#039;'],
            'double quote' => ['"', '&quot;'],
            'greater than' => ['>', '&gt;'],
            'lesser than' => ['<', '&lt;'],
        ];
    }
}
