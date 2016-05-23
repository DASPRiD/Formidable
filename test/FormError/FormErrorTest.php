<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\FormError;

use DASPRiD\Formidable\FormError\FormError;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\FormError\FormError
 */
class FormErrorTest extends TestCase
{
    public function testKeyRetrieval()
    {
        $this->assertSame('foo', (new FormError('foo', ''))->getKey());
    }

    public function testMessageRetrieval()
    {
        $this->assertSame('foo', (new FormError('', 'foo'))->getMessage());
    }

    public function testArgumentsRetrieval()
    {
        $this->assertSame(['foo' => 'bar'], (new FormError('', '', ['foo' => 'bar']))->getArguments());
    }
}
