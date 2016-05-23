<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\FormError;

use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\FormError\FormErrorSequence
 */
class FormErrorSequenceTest extends TestCase
{
    public function testIsEmptyReturnsTrueWithoutErrors()
    {
        $this->assertTrue((new FormErrorSequence())->isEmpty());
    }

    public function testIsEmptyReturnsFalseWithErrors()
    {
        $this->assertFalse((new FormErrorSequence(new FormError('', '')))->isEmpty());
    }

    public function testCountable()
    {
        $this->assertCount(2, new FormErrorSequence(new FormError('', ''), new FormError('', '')));
    }

    public function testIterator()
    {
        $formErrorSequence = new FormErrorSequence(new FormError('foo', 'bar'), new FormError('baz', 'bat'));
        FormErrorAssertion::assertErrorMessages($this, $formErrorSequence, ['foo' => 'bar', 'baz' => 'bat']);
    }

    public function testCollect()
    {
        $this->assertCount(
            2,
            (new FormErrorSequence(
                new FormError('foo', ''),
                new FormError('bar', ''),
                new FormError('foo', '')
            ))->collect('foo')
        );
    }

    public function testMerge()
    {
        $formErrorSequenceA = new FormErrorSequence(new FormError('foo', ''));
        $formErrorSequenceB = new FormErrorSequence(new FormError('bar', ''));
        $formErrorSequenceC = $formErrorSequenceA->merge($formErrorSequenceB);

        $this->assertNotSame($formErrorSequenceA, $formErrorSequenceC);
        $this->assertNotSame($formErrorSequenceB, $formErrorSequenceC);
        $this->assertCount(2, $formErrorSequenceC);
    }
}
