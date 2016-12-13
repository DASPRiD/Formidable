<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Exception;

use DASPRiD\Formidable\Exception\InvalidValueException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Exception\InvalidValueException
 */
class InvalidValueExceptionTest extends TestCase
{
    public function testFromArrayWithNonStringKeys()
    {
        $this->assertSame(
            'Non-string value in array found',
            InvalidValueException::fromArrayWithNonStringValues([])->getMessage()
        );
    }

    public function testFromNonNestedKey()
    {
        $this->assertSame(
            'Expected string or array value, but "boolean" was provided',
            InvalidValueException::fromNonNestedValue(true)->getMessage()
        );
    }
}
