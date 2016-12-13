<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Exception;

use DASPRiD\Formidable\Exception\InvalidKeyException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Exception\InvalidKeyException
 */
class InvalidKeyExceptionTest extends TestCase
{
    public function testFromArrayWithNonStringKeys()
    {
        $this->assertSame(
            'Non-string key in array found',
            InvalidKeyException::fromArrayWithNonStringKeys([])->getMessage()
        );
    }

    public function testFromNonNestedKey()
    {
        $this->assertSame(
            'Expected string or nested integer key, but "boolean" was provided',
            InvalidKeyException::fromNonNestedKey(true)->getMessage()
        );
    }
}
