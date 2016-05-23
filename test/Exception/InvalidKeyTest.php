<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\FormError;

use DASPRiD\Formidable\Exception\InvalidKey;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Exception\InvalidKey
 */
class InvalidKeyTest extends TestCase
{
    public function testFromArrayWithNonStringKeys()
    {
        $this->assertSame('Non-string key in array found', InvalidKey::fromArrayWithNonStringKeys([])->getMessage());
    }

    public function testFromNonNestedKey()
    {
        $this->assertSame(
            'Expected string or nested integer key, but "boolean" was provided',
            InvalidKey::fromNonNestedKey(true)->getMessage()
        );
    }
}
