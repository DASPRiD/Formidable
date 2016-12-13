<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Exception\InvalidUnapplyResultException;
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

/**
 * @covers DASPRiD\Formidable\Mapping\Exception\InvalidUnapplyResultException
 */
class InvalidUnapplyResultExceptionTest extends TestCase
{
    public function testFromInvalidUnapplyResult()
    {
        $this->assertSame(
            'Unapply was expected to return an array, but returned object',
            InvalidUnapplyResultException::fromInvalidUnapplyResult(new stdClass())->getMessage()
        );
    }
}
