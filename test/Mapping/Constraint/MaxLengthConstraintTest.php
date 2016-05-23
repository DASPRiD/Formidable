<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use Assert\AssertionFailedException;
use DASPRiD\Formidable\Mapping\Constraint\MaxLengthConstraint;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\MaxLengthConstraint
 */
class MaxLengthConstraintTest extends TestCase
{
    public function testAssertionWithInvalidLength()
    {
        $this->expectException(AssertionFailedException::class);
        new MaxLengthConstraint(-1);
    }

    public function testAssertionWithInvalidValueType()
    {
        $constraint = new MaxLengthConstraint(0);
        $this->expectException(AssertionFailedException::class);
        $constraint(1);
    }

    public function testFailureWithEmptyString()
    {
        $constraint = new MaxLengthConstraint(1);
        $validationResult = $constraint('ab');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['error.max-length' => ['lengthLimit' => 1]]
        );
    }

    public function testSuccessWithMultiByte()
    {
        $constraint = new MaxLengthConstraint(1);
        $validationResult = $constraint('ü');
        $this->assertTrue($validationResult->isSuccess());
    }

    public function testSuccessWithValidString()
    {
        $constraint = new MaxLengthConstraint(2);
        $validationResult = $constraint('ab');
        $this->assertTrue($validationResult->isSuccess());
    }
}
