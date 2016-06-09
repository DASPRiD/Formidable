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

    public function testKeySuffixRetrieval()
    {
        $this->assertSame('foo', (new ValidationError('', [], 'foo'))->getKeySuffix());
    }

    public function testDefaults()
    {
        $validationError = new ValidationError('');
        $this->assertSame([], $validationError->getArguments());
        $this->assertSame('', $validationError->getKeySuffix());
    }
}
