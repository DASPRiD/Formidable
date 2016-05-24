<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Field;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Helper\InputCheckbox;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\InputCheckbox
 * @covers DASPRiD\Formidable\Helper\AttributeTrait
 */
class InputCheckboxTest extends TestCase
{
    public function testDefaultInputWithEmptyValue()
    {
        $helper = new InputCheckbox();
        $this->assertSame(
            '<input type="checkbox" id="input.foo" name="foo" value="true">',
            $helper(new Field('foo', '', new FormErrorSequence(), Data::none()))
        );
    }

    public function testDefaultInputWithTrueValue()
    {
        $helper = new InputCheckbox();
        $this->assertSame(
            '<input type="checkbox" id="input.foo" name="foo" value="true" checked>',
            $helper(new Field('foo', 'true', new FormErrorSequence(), Data::none()))
        );
    }

    public function testCustomAttribute()
    {
        $helper = new InputCheckbox();
        $this->assertSame(
            '<input data-foo="bar" type="checkbox" id="input.foo" name="foo" value="true">',
            $helper(new Field('foo', '', new FormErrorSequence(), Data::none()), ['data-foo' => 'bar'])
        );
    }
}
