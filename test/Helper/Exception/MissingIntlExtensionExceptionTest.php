<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper\Exception;

use DASPRiD\Formidable\Helper\Exception\MissingIntlExtensionException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\Exception\MissingIntlExtensionException
 */
class MissingIntlExtensionExceptionTest extends TestCase
{
    public function testFromMissingExtension()
    {
        $this->assertSame(
            'You must install the PHP intl extension for this helper to work',
            MissingIntlExtensionException::fromMissingExtension()->getMessage()
        );
    }
}
