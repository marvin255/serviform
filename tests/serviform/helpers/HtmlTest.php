<?php

namespace marvin255\serviform\tests\serviform\helpers;

use PHPUnit_Framework_TestCase;
use marvin255\serviform\helpers\Html;

class HtmlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createTagProvider
     */
    public function testCreateTag($tag, $options, $content, $expected)
    {
        $res = Html::createTag($tag, $options, $content);
        $this->assertSame($expected, $res);
    }

    public function createTagProvider()
    {
        return [
            'tag with content' => [
                'div',
                null,
                'test',
                '<div>test</div>',
            ],
            'tag without content' => [
                'br',
                null,
                false,
                '<br>',
            ],
            'self closing tag' => [
                'br',
                null,
                true,
                '<br>',
            ],
            'tag with attributes' => [
                'div',
                ['data-param-1' => 1, 'data-param-2' => 2, '' => 'empty_key'],
                'test',
                '<div data-param-1="1" data-param-2="2">test</div>',
            ],
            'xss in attribute key' => [
                'div',
                ['onclick="alert(\'xss\')" data-param' => 'test'],
                '',
                '<div onclick--alert--xss----data-param="test"></div>',
            ],
            'xss in attribute value' => [
                'div',
                ['test' => '" onclick="alert(\'xss\')" data-param='],
                '',
                '<div test="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param="></div>',
            ],
            'xss in tag name' => [
                'div onclick="alert(\'xss\')',
                null,
                'test',
                '<div-onclick--alert--xss>test</div-onclick--alert--xss>',
            ],
        ];
    }

    /**
     * @dataProvider createCloseTagProvider
     */
    public function testCreateCloseTag($input, $expected)
    {
        $res = Html::createCloseTag($input);
        $this->assertSame($expected, $res);
    }

    public function createCloseTagProvider()
    {
        return [
            'xss in tag name' => [
                'div onclick="alert(\'xss\')"',
                '</div-onclick--alert--xss>',
            ],
            'valid tag' => [
                'span',
                '</span>',
            ],
            'valid tag' => [
                123,
                '</123>',
            ],
        ];
    }

    /**
     * @dataProvider createAttributeStringProvider
     */
    public function testCreateAttributeString($input, $expected)
    {
        $res = Html::createAttributeString($input);
        $this->assertSame($expected, $res);
    }

    public function createAttributeStringProvider()
    {
        return [
            'xss in key' => [
                ['onclick="alert(\'xss\')" data-param' => 'test'],
                'onclick--alert--xss----data-param="test"',
            ],
            'xss in value' => [
                ['data-param' => '" onclick="alert(\'xss\')" data-param="'],
                'data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;"',
            ],
            'multiple params' => [
                ['data-test-1' => 1, 'data-test-2' => 2],
                'data-test-1="1" data-test-2="2"',
            ],
        ];
    }

    /**
     * @dataProvider clearAttributeKeyProvider
     */
    public function testClearAttributeKey($string, $expected)
    {
        $res = Html::clearAttributeKey($string);
        $this->assertSame($expected, $res);
    }

    public function clearAttributeKeyProvider()
    {
        return [
            'ampersand' => ['&test&test&', 'test-test'],
            'quote' => ["'test'test'", 'test-test'],
            'double quote' => ['"test"test"', 'test-test'],
            'greater than' => ['>test>test>', 'test-test'],
            'lesser than' => ['<test<test<', 'test-test'],
            'equal' => ['=test=test=', 'test-test'],
            'space' => [' test test ', 'test-test'],
            'valid string' => ['test123', 'test123'],
            'multiple replacers string' => ['test   test>>', 'test---test'],
        ];
    }

    public function testClearAttributeKeyWithInvalidValue()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $res = Html::clearAttributeKey('++++++');
    }

    /**
     * @dataProvider clearAttributeValueProvider
     */
    public function testClearAttributeValue($string, $expected)
    {
        $res = Html::clearAttributeValue($string);
        $this->assertSame($expected, $res);
    }

    public function clearAttributeValueProvider()
    {
        return [
            'ampersand' => ['&', '&amp;'],
            'quote' => ["'", '&#039;'],
            'double quote' => ['"', '&quot;'],
            'greater than' => ['>', '&gt;'],
            'lesser than' => ['<', '&lt;'],
            'valid string' => ['test 123', 'test 123'],
            'compound string' => ['"test" 123"', '&quot;test&quot; 123&quot;'],
        ];
    }
}
