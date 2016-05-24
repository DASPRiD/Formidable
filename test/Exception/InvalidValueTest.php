<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Exception;

use DASPRiD\Formidable\Exception\InvalidValue;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Exception\InvalidValue
 */
class InvalidValueTest extends TestCase
{
    public function testFromArrayWithNonStringKeys()
    {
        $this->assertSame(
            'Non-string value in array found',
            InvalidValue::fromArrayWithNonStringValues([])->getMessage()
        );
    }

    public function testFromNonNestedKey()
    {
        $this->assertSame(
            'Expected string or array value, but "boolean" was provided',
            InvalidValue::fromNonNestedValue(true)->getMessage()
        );
    }
}
