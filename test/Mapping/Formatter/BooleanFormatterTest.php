<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Exception\InvalidValue;
use DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter;
use PHPUnit_Framework_TestCase as TestCase;

class BooleanFormatterTest extends TestCase
{
    public function testBindValidTrueValue()
    {
        $data = Data::fromFlatArray(['foo' => 'true']);
        $formatter = new BooleanFormatter();
        $formatter->bind('foo', $data);
        $this->assertSame(true, $formatter->bind('foo', $data)->getValue());
    }

    public function testBindValidFalseValue()
    {
        $data = Data::fromFlatArray(['foo' => 'false']);
        $formatter = new BooleanFormatter();
        $formatter->bind('foo', $data);
        $this->assertSame(false, $formatter->bind('foo', $data)->getValue());
    }

    public function testFallbackToFalseOnBindEmptyKey()
    {
        $data = Data::fromFlatArray([]);
        $formatter = new BooleanFormatter();
        $this->assertSame(false, $formatter->bind('foo', $data)->getValue());
    }

    public function testBindEmptyStringValue()
    {
        $this->markTestIncomplete('Disabled until BindResult::fromFormErrors TypeError is resolved');
//        $data = Data::fromFlatArray(['foo' => '']);
//        $this->expectException(InvalidValue::class);
//        $formatter = new BooleanFormatter();
//        $this->assertSame(false, $formatter->bind('foo', $data)->getValue());
    }

    public function testBindInvalidStringValue()
    {
        $this->markTestIncomplete('Disabled until BindResult::fromFormErrors TypeError is resolved');
//        $data = Data::fromFlatArray(['foo' => 'bar']);
//        $formatter = new BooleanFormatter();
//        $this->expectException(InvalidValue::class);
//        $formatter->bind('foo', $data);
    }

    public function testUnbindValidTrueValue()
    {
        $formatter = new BooleanFormatter();
        $data = $formatter->unbind('foo', true);
        $this->assertSame('true', $data->getValue('foo'));
    }

    public function testUnbindValidFalseValue()
    {
        $formatter = new BooleanFormatter();
        $data = $formatter->unbind('foo', false);
        $this->assertSame('false', $data->getValue('foo'));
    }

    public function testUnbindInvalidStringValue()
    {
        $formatter = new BooleanFormatter();
        $this->expectException(InvalidValue::class);
        $formatter->unbind('foo', 'false');
    }
}
