<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Field;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Helper\Textarea;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\Textarea
 * @covers DASPRiD\Formidable\Helper\AttributeTrait
 */
class TextareaTest extends TestCase
{
    public function testDefaultTextarea()
    {
        $helper = new Textarea();
        $this->assertSame(
            '<textarea id="input.foo" name="foo">bar&amp;</textarea>',
            $helper(new Field('foo', 'bar&', new FormErrorSequence(), Data::none()))
        );
    }

    public function testEmptyTextarea()
    {
        $helper = new Textarea();
        $this->assertSame(
            '<textarea id="input.foo" name="foo"></textarea>',
            $helper(new Field('foo', '', new FormErrorSequence(), Data::none()))
        );
    }

    public function testCustomAttribute()
    {
        $helper = new Textarea();
        $this->assertSame(
            '<textarea data-foo="bar" id="input.foo" name="foo">bar&amp;</textarea>',
            $helper(new Field('foo', 'bar&', new FormErrorSequence(), Data::none()), ['data-foo' => 'bar'])
        );
    }
}
