<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping;

use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Mapping\BindResult;
use DASPRiD\Formidable\Mapping\Exception\InvalidBindResultException;
use DASPRiD\Formidable\Mapping\Exception\ValidBindResultException;
use DASPRiD\FormidableTest\FormError\FormErrorAssertion;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\BindResult
 */
class BindResultTest extends TestCase
{
    public function testBindResultFromValue()
    {
        $bindResult = BindResult::fromValue('foo');
        $this->assertTrue($bindResult->isSuccess());
        $this->assertSame('foo', $bindResult->getValue());
        $this->expectException(ValidBindResultException::class);
        $bindResult->getFormErrorSequence();
    }

    public function testBindResultFromFormErrors()
    {
        $bindResult = BindResult::fromFormErrors(new FormError('foo', 'bar'));
        $this->assertFalse($bindResult->isSuccess());
        FormErrorAssertion::assertErrorMessages(
            $this,
            $bindResult->getFormErrorSequence(),
            [
                'foo' => 'bar',
            ]
        );
        $this->expectException(InvalidBindResultException::class);
        $bindResult->getValue();
    }

    public function testBindResultFromFormErrorSequence()
    {
        $bindResult = BindResult::fromFormErrorSequence(new FormErrorSequence(new FormError('foo', 'bar')));
        $this->assertFalse($bindResult->isSuccess());
        FormErrorAssertion::assertErrorMessages(
            $this,
            $bindResult->getFormErrorSequence(),
            [
                'foo' => 'bar',
            ]
        );
        $this->expectException(InvalidBindResultException::class);
        $bindResult->getValue();
    }
}
