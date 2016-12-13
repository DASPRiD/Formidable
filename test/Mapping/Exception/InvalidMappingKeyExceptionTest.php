<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Exception\InvalidMappingKeyException;
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

/**
 * @covers DASPRiD\Formidable\Mapping\Exception\InvalidMappingKeyException
 */
class InvalidMappingKeyExceptionTest extends TestCase
{
    public function testFromInvalidMappingKey()
    {
        $this->assertSame(
            'Mapping key must be of type string, but got object',
            InvalidMappingKeyException::fromInvalidMappingKey(new stdClass())->getMessage()
        );
    }
}
