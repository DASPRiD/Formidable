<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Exception\NonExistentMappedClassException;
use DASPRiD\Formidable\Mapping\MappingInterface;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Exception\NonExistentMappedClassException
 */
class NonExistentMappedClassExceptionTest extends TestCase
{
    public function testFromNonExistentClass()
    {
        $this->assertSame(
            sprintf('Class with name foo does not exist', MappingInterface::class),
            NonExistentMappedClassException::fromNonExistentClass('foo')->getMessage()
        );
    }
}
