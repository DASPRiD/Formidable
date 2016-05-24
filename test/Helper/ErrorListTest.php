<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest;

use Assert\AssertionFailedException;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Helper\ErrorFormatter;
use DASPRiD\Formidable\Helper\ErrorList;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\ErrorList
 */
class ErrorListTest extends TestCase
{
    public function testRenderEmptyErrorSequence()
    {
        $helper = new ErrorList(new ErrorFormatter());
        $html = $helper(new FormErrorSequence());

        $this->assertSame('', $html);
    }

    public function testRenderMultipleErrors()
    {
        $helper = new ErrorList(new ErrorFormatter());
        $html = $helper(new FormErrorSequence(new FormError('', 'error.required'), new FormError('', 'error.integer')));

        $this->assertXmlStringEqualsXmlString(
            '<ul><li>This field is required</li><li>Integer value expected</li></ul>',
            $html
        );
    }

    public function testRenderWithCustomAttributes()
    {
        $helper = new ErrorList(new ErrorFormatter());
        $html = $helper(
            new FormErrorSequence(new FormError('', 'error.required')),
            ['class' => 'errors', 'data-foo' => 'bar']
        );

        $this->assertXmlStringEqualsXmlString(
            '<ul class="errors" data-foo="bar"><li>This field is required</li></ul>',
            $html
        );
    }

    public function testAssertionOnInvalidAttributeKey()
    {
        $helper = new ErrorList(new ErrorFormatter());
        $this->expectException(AssertionFailedException::class);
        $helper(
            new FormErrorSequence(new FormError('', 'error.required')),
            [1 => 'test']
        );
    }

    public function testAssertionOnInvalidAttributeValue()
    {
        $helper = new ErrorList(new ErrorFormatter());
        $this->expectException(AssertionFailedException::class);
        $helper(
            new FormErrorSequence(new FormError('', 'error.required')),
            ['test' => 1]
        );
    }
}
