<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper;

use DASPRiD\Formidable\Helper\ErrorFormatter;
use DASPRiD\Formidable\Helper\Exception\NonExistentMessage;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\ErrorFormatter
 */
class ErrorFormatterTest extends TestCase
{
    /**
     * @dataProvider builtInMessageProvider
     */
    public function testBuiltInMessages(string $key, string $expectedMessage, array $arguments = [])
    {
        $errorFormatter = new ErrorFormatter();
        $message = $errorFormatter($key, $arguments);

        $this->assertSame($expectedMessage, $message);
    }

    public function builtInMessageProvider()
    {
        return [
            'error.required' => ['error.required', 'This field is required'],
            'error.empty' => ['error.empty', 'Value must not be empty'],
            'error.integer' => ['error.integer', 'Integer value expected'],
            'error.float' => ['error.float', 'Float value expected'],
            'error.boolean' => ['error.boolean', 'Boolean value expected'],
            'error.date' => ['error.date', 'Date value expected'],
            'error.time' => ['error.time', 'Time value expected'],
            'error.date-time' => ['error.date-time', 'Datetime value expected'],
            'error.email-address' => ['error.email-address', 'Valid email address required'],
            'error.min-length.singular' => ['error.min-length', 'Minimum length is 1 character', ['lengthLimit' => 1]],
            'error.min-length.plural' => ['error.min-length', 'Minimum length is 2 characters', ['lengthLimit' => 2]],
            'error.max-length.singular' => ['error.max-length', 'Maximum length is 1 character', ['lengthLimit' => 1]],
            'error.max-length.plural' => ['error.max-length', 'Maximum length is 2 characters', ['lengthLimit' => 2]],
            'error.min-number' => ['error.min-number', 'Minimum value is 1.5', ['limit' => '1.500']],
            'error.max-number' => ['error.max-number', 'Maximum value is 3.5', ['limit' => '3.500']],
            'error.step-number' => ['error.step-number', 'Value is invalid, closest valid values are 3.5 and 4.5', [
                'lowValue' => '3.500',
                'highValue' => '4.500',
            ]],
        ];
    }

    public function testOverrideBuiltInMessage()
    {
        $errorFormatter = new ErrorFormatter(['error.required' => 'foo']);
        $this->assertSame('foo', $errorFormatter('error.required'));
    }

    public function testCustomMessage()
    {
        $errorFormatter = new ErrorFormatter(['error.foo' => 'bar']);
        $this->assertSame('bar', $errorFormatter('error.foo'));
    }

    public function testExceptionOnNonExistentMessage()
    {
        $errorFormatter = new ErrorFormatter();
        $this->expectException(NonExistentMessage::class);
        $errorFormatter('error.foo');
    }
}
