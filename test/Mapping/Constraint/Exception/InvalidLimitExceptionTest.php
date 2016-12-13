<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLimitException;
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLimitException
 */
class InvalidLimitExceptionTest extends TestCase
{
    public function testFromNonNumericValueWithString()
    {
        $this->assertSame(
            'Limit was expected to be numeric, but got "test"',
            InvalidLimitException::fromNonNumericValue('test')->getMessage()
        );
    }

    public function testFromNonNumericValueWithObject()
    {
        $this->assertSame(
            'Limit was expected to be numeric, but got object',
            InvalidLimitException::fromNonNumericValue(new stdClass())->getMessage()
        );
    }
}
