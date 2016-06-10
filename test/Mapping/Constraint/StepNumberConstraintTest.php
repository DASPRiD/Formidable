<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use Assert\AssertionFailedException;
use DASPRiD\Formidable\Mapping\Constraint\StepNumberConstraint;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\StepNumberConstraint
 */
class StepNumberConstraintTest extends TestCase
{
    public function validStepProvider() : array
    {
        return [
            // Integers
            [1, null, 0],
            [1, null, -1],
            [1, null, 1],
            [2, 1, 3],
            [2, 1, -1],

            // Floats
            [0.7, null, 0.7],
            [0.7, 0.3, 1.],
            [0.7, 0.3, -0.4],

            // Decimals
            ['0.7', null, '0.7'],
            ['0.7', '0.3', '1'],
            ['5', '2', '7'],
            ['5', '2', '-3'],
        ];
    }

    /**
     * @dataProvider validStepProvider
     * @param int|float|string $step
     * @param int|float|string|null $base
     * @param int|float|string $value
     */
    public function testValidSteps($step, $base, $value)
    {
        $constraint = new StepNumberConstraint($step, $base);
        $this->assertTrue($constraint($value)->isSuccess());
    }

    public function invalidStepProvider() : array
    {
        return [
            // Integers
            [2, -1, 0, '-1', '1'],
            [2, null, -1, '-2', '0'],
            [2, null, 1, '0', '2'],

            // Floats
            [0.7, null, 0.35, '0', '0.7'],
            [0.7, null, 0.71, '0.7', '1.4'],
            [0.7, null, 0.70000000000001, '0.7', '1.4'],

            // Decimals
            ['0.7', null, '0.35', '0', '0.7'],
            ['0.7', null, '0.71', '0.7', '1.4'],
        ];
    }

    /**
     * @dataProvider invalidStepProvider
     * @param int|float|string $step
     * @param int|float|string|null $base
     * @param int|float|string $value
     * @param string $lowValue
     * @param string $highValue
     */
    public function testInvalidSteps($step, $base, $value, string $lowValue, string $highValue)
    {
        $constraint = new StepNumberConstraint($step, $base);
        $validationResult = $constraint($value);
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['error.step-number' => ['lowValue' => $lowValue, 'highValue' => $highValue]]
        );
    }

    public function testAssertionWithNegativeIntegerStep()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint(-1);
    }

    public function testAssertionWithZeroIntegerStep()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint(0);
    }

    public function testAssertionWithNegativeFloatStep()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint(-1.);
    }

    public function testAssertionWithZeroFloatStep()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint(0.);
    }

    public function testAssertionWithNegativeDecimalStep()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint('-1');
    }

    public function testAssertionWithZeroDecimalStep()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint('0');
    }

    public function testAssertionWithNonNumericStep()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint('test');
    }

    public function testAssertionWithNonNumericBase()
    {
        $this->expectException(AssertionFailedException::class);
        new StepNumberConstraint(1, 'test');
    }

    public function testAssertionWithNonNumericValue()
    {
        $constraint = new StepNumberConstraint(1);
        $this->expectException(AssertionFailedException::class);
        $constraint('test');
    }
}
