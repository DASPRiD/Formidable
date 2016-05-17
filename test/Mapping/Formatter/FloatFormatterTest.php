<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidValue;
use DASPRiD\Formidable\Mapping\Formatter\FloatFormatter;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\FloatFormatter
 */
class FloatFormatterTest extends TestCase
{
    public function testBindValidPositiveValue()
    {
        $this->assertSame(42.12, (new FloatFormatter())->bind(
            'foo',
            Data::fromFlatArray(['foo' => '42.12'])
        )->getValue());
    }

    public function testBindValidNegativeValue()
    {
        $this->assertSame(-42.12, (new FloatFormatter())->bind(
            'foo',
            Data::fromFlatArray(['foo' => '-42.12'])
        )->getValue());
    }

    public function testBindEmptyStringValue()
    {
        $bindResult = (new FloatFormatter())->bind('foo', Data::fromFlatArray(['foo' => '']));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrors());

        $error = iterator_to_array($bindResult->getFormErrors())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.float', $error->getMessage());
    }

    public function testThrowErrorOnBindNonExistentKey()
    {
        $bindResult = (new FloatFormatter())->bind('foo', Data::fromFlatArray([]));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrors());

        $error = iterator_to_array($bindResult->getFormErrors())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.required', $error->getMessage());
    }

    public function testUnbindValidPositiveValue()
    {
        $data = (new FloatFormatter())->unbind('foo', 42.12);
        $this->assertSame('42.12', $data->getValue('foo'));
    }

    public function testUnbindValidNegativeValue()
    {
        $data = (new FloatFormatter())->unbind('foo', -42.12);
        $this->assertSame('-42.12', $data->getValue('foo'));
    }

    public function testUnbindInvalidFloatValue()
    {
        $this->expectException(InvalidValue::class);
        (new FloatFormatter())->unbind('foo', '');
    }
}
