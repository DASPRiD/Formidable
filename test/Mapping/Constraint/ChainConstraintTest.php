<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\ChainConstraint;
use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;
use DASPRiD\Formidable\Mapping\Constraint\ValidationError;
use DASPRiD\Formidable\Mapping\Constraint\ValidationResult;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\ChainConstraint
 */
class ChainConstraintTest extends TestCase
{
    public function testSuccessfulChain()
    {
        $constraintA = $this->prophesize(ConstraintInterface::class);
        $constraintA->__invoke('a')->willReturn(new ValidationResult());

        $constraintB = $this->prophesize(ConstraintInterface::class);
        $constraintB->__invoke('a')->willReturn(new ValidationResult());

        $constraint = new ChainConstraint(true, $constraintA->reveal(), $constraintB->reveal());
        $validationResult = $constraint('a');
        $this->assertTrue($validationResult->isSuccess());
    }

    public function testBreakingChain()
    {
        $constraintA = $this->prophesize(ConstraintInterface::class);
        $constraintA->__invoke('a')->willReturn(new ValidationResult(new ValidationError('a')));

        $constraintB = $this->prophesize(ConstraintInterface::class);
        $constraintB->__invoke('a')->willReturn(new ValidationResult(new ValidationError('b')));

        $constraint = new ChainConstraint(true, $constraintA->reveal(), $constraintB->reveal());
        $validationResult = $constraint('a');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['a' => []]
        );
    }

    public function testNonBreakingChainWithMultipleFailures()
    {
        $constraintA = $this->prophesize(ConstraintInterface::class);
        $constraintA->__invoke('a')->willReturn(new ValidationResult(new ValidationError('a')));

        $constraintB = $this->prophesize(ConstraintInterface::class);
        $constraintB->__invoke('a')->willReturn(new ValidationResult(new ValidationError('b')));

        $constraint = new ChainConstraint(false, $constraintA->reveal(), $constraintB->reveal());
        $validationResult = $constraint('a');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['a' => [], 'b' => []]
        );
    }

    public function testNonBreakingChainWithLastFailure()
    {
        $constraintA = $this->prophesize(ConstraintInterface::class);
        $constraintA->__invoke('a')->willReturn(new ValidationResult());

        $constraintB = $this->prophesize(ConstraintInterface::class);
        $constraintB->__invoke('a')->willReturn(new ValidationResult(new ValidationError('b')));

        $constraint = new ChainConstraint(false, $constraintA->reveal(), $constraintB->reveal());
        $validationResult = $constraint('a');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages(
            $this,
            $validationResult,
            ['b' => []]
        );
    }
}
