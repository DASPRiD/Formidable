<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\ValidationError;
use DASPRiD\Formidable\Mapping\Constraint\ValidationResult;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\ValidationResult
 */
class ValidationResultTest extends TestCase
{
    public function testSuccessWithoutErrors()
    {
        $this->assertTrue((new ValidationResult())->isSuccess());
    }

    public function testFailureWithErrors()
    {
        $this->assertFalse((new ValidationResult(new ValidationError('')))->isSuccess());
    }

    public function testValidationErrorsRetrieval()
    {
        $validationResult = new ValidationResult(new ValidationError('foo'), new ValidationError('bar'));
        ValidationErrorAssertion::assertErrorMessages($this, $validationResult, ['foo' => [], 'bar' => []]);
    }

    public function testMerge()
    {
        $validationResultA = new ValidationResult(new ValidationError('foo'));
        $validationResultB = new ValidationResult();
        $validationResultC = new ValidationResult(new ValidationError('bar'), new ValidationError('baz'));

        $validationResult = $validationResultA->merge($validationResultB)->merge($validationResultC);
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['foo' => [], 'bar' => [], 'baz' => []]
        );
    }
}
