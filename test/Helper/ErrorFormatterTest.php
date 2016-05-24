<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest;

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
            'error.integer' => ['error.integer', 'Integer value expected'],
            'error.float' => ['error.float', 'Float value expected'],
            'error.boolean' => ['error.boolean', 'Boolean value expected'],
            'error.email-address' => ['error.email-address', 'Valid email address required'],
            'error.min-length.singular' => ['error.min-length', 'Minimum length is 1 character', ['lengthLimit' => 1]],
            'error.min-length.plural' => ['error.min-length', 'Minimum length is 2 characters', ['lengthLimit' => 2]],
            'error.max-length.singular' => ['error.max-length', 'Maximum length is 1 character', ['lengthLimit' => 1]],
            'error.max-length.plural' => ['error.max-length', 'Maximum length is 2 characters', ['lengthLimit' => 2]],
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
