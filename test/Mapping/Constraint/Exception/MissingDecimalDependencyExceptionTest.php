<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Exception;

use DASPRiD\Formidable\Mapping\Constraint\Exception\MissingDecimalDependencyException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\Exception\MissingDecimalDependencyException
 */
class MissingDecimalDependencyExceptionTest extends TestCase
{
    public function testFromMissingDependency()
    {
        $this->assertSame(
            'You must composer require litipk/php-bignumbers for this constraint to work',
            MissingDecimalDependencyException::fromMissingDependency()->getMessage()
        );
    }
}
