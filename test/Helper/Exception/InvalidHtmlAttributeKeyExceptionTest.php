<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper\Exception;

use DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeKeyException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeKeyException
 */
class InvalidHtmlAttributeKeyExceptionTest extends TestCase
{
    public function testFromInvalidKey()
    {
        $this->assertSame(
            'HTML attribute key must be of type string, but got integer',
            InvalidHtmlAttributeKeyException::fromInvalidKey(1)->getMessage()
        );
    }
}
