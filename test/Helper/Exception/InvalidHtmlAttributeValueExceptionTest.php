<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper\Exception;

use DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeValueException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeValueException
 */
class InvalidHtmlAttributeValueExceptionTest extends TestCase
{
    public function testFromInvalidValue()
    {
        $this->assertSame(
            'HTML attribute value must be of type string, but got integer',
            InvalidHtmlAttributeValueException::fromInvalidValue(1)->getMessage()
        );
    }
}
