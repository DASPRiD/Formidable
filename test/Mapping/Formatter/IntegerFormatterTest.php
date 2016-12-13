<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidTypeException;
use DASPRiD\Formidable\Mapping\Formatter\IntegerFormatter;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\IntegerFormatter
 */
class IntegerFormatterTest extends TestCase
{
    public function testBindValidPositiveValue()
    {
        $this->assertSame(42, (new IntegerFormatter())->bind(
            'foo',
            Data::fromFlatArray(['foo' => '42'])
        )->getValue());
    }

    public function testBindValidNegativeValue()
    {
        $this->assertSame(-42, (new IntegerFormatter())->bind(
            'foo',
            Data::fromFlatArray(['foo' => '-42'])
        )->getValue());
    }

    public function testBindInvalidFloatValue()
    {
        $bindResult = (new IntegerFormatter())->bind('foo', Data::fromFlatArray(['foo' => '1.1']));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.integer', $error->getMessage());
    }

    public function testBindEmptyStringValue()
    {
        $bindResult = (new IntegerFormatter())->bind('foo', Data::fromFlatArray(['foo' => '']));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.integer', $error->getMessage());
    }

    public function testThrowErrorOnBindNonExistentKey()
    {
        $bindResult = (new IntegerFormatter())->bind('foo', Data::fromFlatArray([]));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.required', $error->getMessage());
    }

    public function testUnbindValidPositiveValue()
    {
        $data = (new IntegerFormatter())->unbind('foo', 42);
        $this->assertSame('42', $data->getValue('foo'));
    }

    public function testUnbindValidNegativeValue()
    {
        $data = (new IntegerFormatter())->unbind('foo', -42);
        $this->assertSame('-42', $data->getValue('foo'));
    }

    public function testUnbindInvalidFloatValue()
    {
        $this->expectException(InvalidTypeException::class);
        (new IntegerFormatter())->unbind('foo', 1.1);
    }
}
