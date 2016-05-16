<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter\Exception;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidValue;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidValue
 */
class InvalidValueTest extends TestCase
{
    public function testMessageFromNonBoolean()
    {
        $this->assertSame(
            'Expected boolean, but "string" was provided',
            InvalidValue::fromNonBoolean('foo')->getMessage()
        );
    }

    public function testMessageFromNonInteger()
    {
        $this->assertSame(
            'Expected integer, but "string" was provided',
            InvalidValue::fromNonInteger('foo')->getMessage()
        );
    }

    public function testMessageFromNonString()
    {
        $this->assertSame(
            'Expected string, but "boolean" was provided',
            InvalidValue::fromNonString(true)->getMessage()
        );
    }
}
