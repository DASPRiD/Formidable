<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Exception;

use DASPRiD\Formidable\Exception\NonExistentKey;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Exception\NonExistentKey
 */
class NonExistentKeyTest extends TestCase
{
    public function testFromNonExistentKey()
    {
        $this->assertSame(
            'Non-existent key "foo" provided',
            NonExistentKey::fromNonExistentKey('foo')->getMessage()
        );
    }
}
