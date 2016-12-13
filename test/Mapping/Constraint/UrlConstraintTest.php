<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidTypeException;
use DASPRiD\Formidable\Mapping\Constraint\UrlConstraint;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Constraint\UrlConstraint
 */
class UrlConstraintTest extends TestCase
{
    public function testAssertionWithInvalidValueType()
    {
        $constraint = new UrlConstraint();
        $this->expectException(InvalidTypeException::class);
        $constraint(1);
    }

    public function testFailureWithEmptyString()
    {
        $constraint = new UrlConstraint();
        $validationResult = $constraint('');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages($this, $validationResult, ['error.url' => []]);
    }

    public function testFailureWithInvalidUrl()
    {
        $constraint = new UrlConstraint();
        $validationResult = $constraint('foobar');
        $this->assertFalse($validationResult->isSuccess());
        ValidationErrorAssertion::assertErrorMessages($this, $validationResult, ['error.url' => []]);
    }

    public function testSuccessWithValidHttpUrl()
    {
        $constraint = new UrlConstraint();
        $validationResult = $constraint('http://example.com');
        $this->assertTrue($validationResult->isSuccess());
    }

    public function testSuccessWithValidHttpUrlWithLocalhost()
    {
        $constraint = new UrlConstraint();
        $validationResult = $constraint('http://localhost');
        $this->assertTrue($validationResult->isSuccess());
    }

    public function testSuccessWithValidIrcUrl()
    {
        $constraint = new UrlConstraint();
        $validationResult = $constraint('irc://example.com');
        $this->assertTrue($validationResult->isSuccess());
    }
}
