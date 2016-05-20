<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\ValidationError;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\ValidationError
 */
class ValidationErrorTest extends TestCase
{
    public function testMessageRetrieval()
    {
        $this->assertSame('foo', (new ValidationError('foo'))->getMessage());
    }

    public function testArgumentsRetrieval()
    {
        $this->assertSame(['foo'], (new ValidationError('', ['foo']))->getArguments());
    }
}
