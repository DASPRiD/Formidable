<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Exception\InvalidBindResultException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Exception\InvalidBindResultException
 */
class InvalidBindResultExceptionTest extends TestCase
{
    public function testFromGetValueAttempt()
    {
        $this->assertSame(
            'Value can only be retrieved when bind result was successful',
            InvalidBindResultException::fromGetValueAttempt()->getMessage()
        );
    }
}
