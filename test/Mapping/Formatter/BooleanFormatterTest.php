<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use Assert\AssertionFailedException;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter
 */
class BooleanFormatterTest extends TestCase
{
    public function testBindValidTrueValue()
    {
        $this->assertTrue((new BooleanFormatter())->bind('foo', Data::fromFlatArray(['foo' => 'true']))->getValue());
    }

    public function testBindValidFalseValue()
    {
        $this->assertFalse((new BooleanFormatter())->bind('foo', Data::fromFlatArray(['foo' => 'false']))->getValue());
    }

    public function testFallbackToFalseOnBindNonExistentKey()
    {
        $this->assertFalse((new BooleanFormatter())->bind('foo', Data::fromFlatArray([]))->getValue());
    }

    public function testBindEmptyStringValue()
    {
        $bindResult = (new BooleanFormatter())->bind('foo', Data::fromFlatArray(['foo' => '']));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.boolean', $error->getMessage());
    }

    public function testUnbindValidTrueValue()
    {
        $data = (new BooleanFormatter())->unbind('foo', true);
        $this->assertSame('true', $data->getValue('foo'));
    }

    public function testUnbindValidFalseValue()
    {
        $data = (new BooleanFormatter())->unbind('foo', false);
        $this->assertSame('false', $data->getValue('foo'));
    }

    public function testUnbindInvalidStringValue()
    {
        $this->expectException(AssertionFailedException::class);
        (new BooleanFormatter())->unbind('foo', 'false');
    }
}
