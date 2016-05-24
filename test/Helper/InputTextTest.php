<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Field;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Helper\InputText;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\InputText
 * @covers DASPRiD\Formidable\Helper\AttributeTrait
 */
class InputTextTest extends TestCase
{
    public function testDefaultInput()
    {
        $helper = new InputText();
        $this->assertSame(
            '<input type="text" id="input.foo" name="foo" value="bar&amp;">',
            $helper(new Field('foo', 'bar&', new FormErrorSequence(), Data::none()))
        );
    }

    public function testCustomInputType()
    {
        $helper = new InputText();
        $this->assertSame(
            '<input type="email" id="input.foo" name="foo" value="bar&amp;">',
            $helper(new Field('foo', 'bar&', new FormErrorSequence(), Data::none()), ['type' => 'email'])
        );
    }

    public function testCustomAttribute()
    {
        $helper = new InputText();
        $this->assertSame(
            '<input data-foo="bar" type="text" id="input.foo" name="foo" value="bar&amp;">',
            $helper(new Field('foo', 'bar&', new FormErrorSequence(), Data::none()), ['data-foo' => 'bar'])
        );
    }
}
