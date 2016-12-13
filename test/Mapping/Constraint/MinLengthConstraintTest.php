<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLengthException;
use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidTypeException;
use DASPRiD\Formidable\Mapping\Constraint\MinLengthConstraint;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\MinLengthConstraint
 */
class MinLengthConstraintTest extends TestCase
{
    public function testAssertionWithInvalidLength()
    {
        $this->expectException(InvalidLengthException::class);
        new MinLengthConstraint(-1);
    }

    public function testAssertionWithInvalidValueType()
    {
        $constraint = new MinLengthConstraint(0);
        $this->expectException(InvalidTypeException::class);
        $constraint(1);
    }

    public function testFailureWithEmptyString()
    {
        $constraint = new MinLengthConstraint(1);
        $validationResult = $constraint('');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['error.min-length' => ['lengthLimit' => 1]]
        );
    }

    public function testFailureWithMultiByte()
    {
        $constraint = new MinLengthConstraint(2);
        $validationResult = $constraint('ü');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['error.min-length' => ['lengthLimit' => 2]]
        );
    }

    public function testSuccessWithValidString()
    {
        $constraint = new MinLengthConstraint(2);
        $validationResult = $constraint('ab');
        $this->assertTrue($validationResult->isSuccess());
    }
}
