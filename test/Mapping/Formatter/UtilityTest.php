<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use DASPRiD\Formidable\Mapping\Formatter\Utility;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\Utility
 */
class UtilityTest extends TestCase
{
    public function testCreateStringAssertionExceptionMessage()
    {
        $this->assertSame(
            'Value "1" in element "bar" expected to be type string. Type integer given.',
            Utility::createStringAssertionExceptionMessage(1, 'bar')
        );
        $this->assertSame(
            'Value "<TRUE>" in element "bar" expected to be type string. Type boolean given.',
            Utility::createStringAssertionExceptionMessage(true, 'bar')
        );
        $this->assertSame(
            'Value "<ARRAY>" in element "bar" expected to be type string. Type array given.',
            Utility::createStringAssertionExceptionMessage([], 'bar')
        );
        $this->assertSame(
            'Value "stdClass" in element "bar" expected to be type string. Type object given.',
            Utility::createStringAssertionExceptionMessage(new \stdClass(), 'bar')
        );
        $handle = fopen('php://memory', 'r');
        $this->assertSame(
            'Value "<RESOURCE>" in element "bar" expected to be type string. Type resource given.',
            Utility::createStringAssertionExceptionMessage(
                $handle,
                'bar'
            )
        );
        fclose($handle);
    }

    public function testCreateIntegerAssertionExceptionMessage()
    {
        $this->assertSame(
            'Value "foo" in element "bar" expected to be type integer. Type string given.',
            Utility::createIntegerAssertionExceptionMessage('foo', 'bar')
        );
        $this->assertSame(
            'Value "foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarb'
            . '..." in element "bar" expected to be type integer. Type string given.',
            Utility::createIntegerAssertionExceptionMessage(
                'foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz foobarbaz '
                . 'foobarbaz',
                'bar'
            )
        );
        $this->assertSame(
            'Value "<NULL>" in element "bar" expected to be type integer. Type NULL given.',
            Utility::createIntegerAssertionExceptionMessage(null, 'bar')
        );
    }
}
