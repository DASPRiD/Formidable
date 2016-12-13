<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\DecimalFormatter;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidTypeException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\DecimalFormatter
 */
class DecimalFormatterTest extends TestCase
{
    public function testBindValidPositiveValue()
    {
        $this->assertSame('42.12', (new DecimalFormatter())->bind(
            'foo',
            Data::fromFlatArray(['foo' => '42.12'])
        )->getValue());
    }

    public function testBindValidNegativeValue()
    {
        $this->assertSame('-42.12', (new DecimalFormatter())->bind(
            'foo',
            Data::fromFlatArray(['foo' => '-42.12'])
        )->getValue());
    }

    public function testBindEmptyStringValue()
    {
        $bindResult = (new DecimalFormatter())->bind('foo', Data::fromFlatArray(['foo' => '']));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.float', $error->getMessage());
    }

    public function testThrowErrorOnBindNonExistentKey()
    {
        $bindResult = (new DecimalFormatter())->bind('foo', Data::fromFlatArray([]));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.required', $error->getMessage());
    }

    public function testUnbindValidPositiveValue()
    {
        $data = (new DecimalFormatter())->unbind('foo', '42.12');
        $this->assertSame('42.12', $data->getValue('foo'));
    }

    public function testUnbindValidNegativeValue()
    {
        $data = (new DecimalFormatter())->unbind('foo', '-42.12');
        $this->assertSame('-42.12', $data->getValue('foo'));
    }

    public function testUnbindInvalidFloatValue()
    {
        $this->expectException(InvalidTypeException::class);
        (new DecimalFormatter())->unbind('foo', 1.1);
    }

    public function testUnbindInvalidStringValue()
    {
        $this->expectException(InvalidTypeException::class);
        (new DecimalFormatter())->unbind('foo', 'test');
    }
}
