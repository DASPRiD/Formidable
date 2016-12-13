<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\IgnoredFormatter;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\IgnoredFormatter
 */
class IgnoredFormatterTest extends TestCase
{
    public function testBindValue()
    {
        $this->assertSame(
            'foo',
            (new IgnoredFormatter('foo'))->bind('foo', Data::fromFlatArray(['foo' => 'baz']))->getValue()
        );
    }

    public function testUnbindValue()
    {
        $data = (new IgnoredFormatter('foo'))->unbind('foo', 'bar');
        $this->assertTrue($data->isEmpty());
    }
}
