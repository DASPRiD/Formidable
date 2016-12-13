<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Exception\ValidBindResultException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Exception\ValidBindResultException
 */
class ValidBindResultExceptionTest extends TestCase
{
    public function testFromGetFormErrorsAttempt()
    {
        $this->assertSame(
            'Form errors can only be retrieved when bind result was not successful',
            ValidBindResultException::fromGetFormErrorsAttempt()->getMessage()
        );
    }
}
