<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLimitException;
use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidTypeException;
use DASPRiD\Formidable\Mapping\Constraint\MaxNumberConstraint;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\MaxNumberConstraint
 */
class MaNumberConstraintTest extends TestCase
{
    public function validValueProvider() : array
    {
        return [
            [0, 0],
            [0, -1],
            [0., 0.],
            [0., -0.1],
            ['0', '0'],
            ['0', '-0.1'],
        ];
    }

    /**
     * @dataProvider validValueProvider
     * @param int|float|string $limit
     * @param int|float|string $value
     */
    public function testValidValues($limit, $value)
    {
        $constraint = new MaxNumberConstraint($limit);
        $validationResult = $constraint($value);
        $this->assertTrue($validationResult->isSuccess());
    }

    public function invalidValueProvider() : array
    {
        return [
            [0, 1],
            [0., 1.],
            ['0', '1'],
        ];
    }

    /**
     * @dataProvider invalidValueProvider
     * @param int|float|string $limit
     * @param int|float|string $value
     */
    public function testInvalidValues($limit, $value)
    {
        $constraint = new MaxNumberConstraint($limit);
        $validationResult = $constraint($value);
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['error.max-number' => ['limit' => (string) $limit]]
        );
    }

    public function testAssertionWithInvalidLimitType()
    {
        $this->expectException(InvalidLimitException::class);
        new MaxNumberConstraint('test');
    }

    public function testAssertionWithNonNumericValueType()
    {
        $constraint = new MaxNumberConstraint(0);
        $this->expectException(InvalidTypeException::class);
        $constraint('test');
    }
}
