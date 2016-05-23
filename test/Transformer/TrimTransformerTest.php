<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping;

use DASPRiD\Formidable\Transformer\TrimTransformer;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Transformer\TrimTransformer
 */
class TrimTransformerTest extends TestCase
{
    public function testTransform()
    {
        $transformer = new TrimTransformer();
        $this->assertSame('foo', $transformer("\0\r\n foo\0\r\n ", ''));
    }
}
