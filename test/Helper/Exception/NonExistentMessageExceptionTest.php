<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper\Exception;

use DASPRiD\Formidable\Helper\Exception\NonExistentMessageException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\Exception\NonExistentMessageException
 */
class NonExistentMessageExceptionTest extends TestCase
{
    public function testFromNonExistentMessageKey()
    {
        $this->assertSame(
            'Non-existent message key "foo" provided',
            NonExistentMessageException::fromNonExistentMessageKey('foo')->getMessage()
        );
    }
}
