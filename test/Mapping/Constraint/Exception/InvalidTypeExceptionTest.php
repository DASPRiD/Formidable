<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint\Exception;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidTypeException;
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidTypeException
 */
class InvalidTypeExceptionTest extends TestCase
{
    public function testFromInvalidTypeWithObject()
    {
        $this->assertSame(
            'Value was expected to be of type foo, but got stdClass',
            InvalidTypeException::fromInvalidType(new stdClass(), 'foo')->getMessage()
        );
    }

    public function testFromInvalidTypeWithScalar()
    {
        $this->assertSame(
            'Value was expected to be of type foo, but got boolean',
            InvalidTypeException::fromInvalidType(true, 'foo')->getMessage()
        );
    }

    public function testFromNonNumericValueWithString()
    {
        $this->assertSame(
            'Value was expected to be numeric, but got "test"',
            InvalidTypeException::fromNonNumericValue('test')->getMessage()
        );
    }

    public function testFromNonNumericValueWithObject()
    {
        $this->assertSame(
            'Value was expected to be numeric, but got object',
            InvalidTypeException::fromNonNumericValue(new stdClass())->getMessage()
        );
    }
}
