<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLengthException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLengthException
 */
class InvalidLengthExceptionTest extends TestCase
{
    public function testFromNegativeLength()
    {
        $this->assertSame(
            'Length must be greater than or equal to zero, but got -1',
            InvalidLengthException::fromNegativeLength(-1)->getMessage()
        );
    }
}
