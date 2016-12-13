<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Exception\InvalidMappingException;
use DASPRiD\Formidable\Mapping\MappingInterface;
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

/**
 * @covers DASPRiD\Formidable\Mapping\Exception\InvalidMappingException
 */
class InvalidMappingExceptionTest extends TestCase
{
    public function testFromInvalidMappingWithObject()
    {
        $this->assertSame(
            sprintf('Mapping was expected to implement %s, but got stdClass', MappingInterface::class),
            InvalidMappingException::fromInvalidMapping(new stdClass())->getMessage()
        );
    }

    public function testFromInvalidMappingWithScalar()
    {
        $this->assertSame(
            sprintf('Mapping was expected to implement %s, but got boolean', MappingInterface::class),
            InvalidMappingException::fromInvalidMapping(true)->getMessage()
        );
    }
}
