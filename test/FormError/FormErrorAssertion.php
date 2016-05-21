<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\FormError;

use DASPRiD\Formidable\FormError\FormErrorSequence;
use PHPUnit_Framework_TestCase as TestCase;

class FormErrorAssertion
{
    public static function assertErrorMessages(
        TestCase $testCase,
        FormErrorSequence $formErrorSequence,
        array $expectedMessages
    ) {
        $actualMessages = [];

        foreach ($formErrorSequence as $formError) {
            $actualMessages[$formError->getKey()] = $formError->getMessage();
        }

        $testCase->assertSame($expectedMessages, $actualMessages);
    }
}
