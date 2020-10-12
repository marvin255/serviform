<?php

declare(strict_types=1);

namespace Marvin255\Serviform\Tests\Helper;

use InvalidArgumentException;
use Marvin255\Serviform\Helper\HtmlHelper;
use Marvin255\Serviform\Tests\BaseTestCase;

class HtmlHelperTest extends BaseTestCase
{
    /**
     * @dataProvider tagProvider
     */
    public function testTag(string $name, string $content, array $attributes, string $result): void
    {
        $tagString = HtmlHelper::tag($name, $content, $attributes);

        $this->assertSame($result, $tagString);
    }

    public function tagProvider(): array
    {
        return [
            [
                'test-tag',
                'test',
                [
                    'test' => 'test',
                    'test-1' => 'test 1',
                ],
                '<test-tag test="test" test-1="test 1">test</test-tag>',
            ],
            [
                'test',
                '',
                [
                    'test-single-quote' => "'",
                ],
                '<test test-single-quote="&#039;"></test>',
            ],
            [
                'test',
                '',
                [
                    'test-double-quote' => '"',
                ],
                '<test test-double-quote="&quot;"></test>',
            ],
            [
                'test',
                '',
                [
                    'test-gt' => '>',
                ],
                '<test test-gt="&gt;"></test>',
            ],
            [
                'test',
                '',
                [
                    'test-lt' => '<',
                ],
                '<test test-lt="&lt;"></test>',
            ],
            [
                'test',
                '',
                [
                    'test-amp' => '&',
                ],
                '<test test-amp="&amp;"></test>',
            ],
        ];
    }

    public function testTagTagNameException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        HtmlHelper::tag('<_123');
    }

    public function testTagAttributeNameException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        HtmlHelper::tag('test', '', ['<_123' => 'test']);
    }

    /**
     * @dataProvider selfClosedTagProvider
     */
    public function testSelfClosedTag(string $name, array $attributes, string $result): void
    {
        $tagString = HtmlHelper::selfClosedTag($name, $attributes);

        $this->assertSame($result, $tagString);
    }

    public function selfClosedTagProvider(): array
    {
        return [
            [
                'test',
                [
                    'test' => 'test',
                    'test-1' => 'test 1',
                ],
                '<test test="test" test-1="test 1" />',
            ],
            [
                'test',
                [
                    'test-single-quote' => "'",
                ],
                '<test test-single-quote="&#039;" />',
            ],
            [
                'test',
                [
                    'test-double-quote' => '"',
                ],
                '<test test-double-quote="&quot;" />',
            ],
            [
                'test',
                [
                    'test-gt' => '>',
                ],
                '<test test-gt="&gt;" />',
            ],
            [
                'test',
                [
                    'test-lt' => '<',
                ],
                '<test test-lt="&lt;" />',
            ],
            [
                'test',
                [
                    'test-amp' => '&',
                ],
                '<test test-amp="&amp;" />',
            ],
        ];
    }

    public function testSelfClosedTagTagNameException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        HtmlHelper::selfClosedTag('<_123');
    }

    public function testSelfClosedTagAttributeNameException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        HtmlHelper::selfClosedTag('test', ['<_123' => 'test']);
    }
}
