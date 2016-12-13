<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Helper;

use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Helper\ErrorFormatter;
use DASPRiD\Formidable\Helper\ErrorList;
use DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeKeyException;
use DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeValueException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Helper\ErrorList
 * @covers DASPRiD\Formidable\Helper\AttributeTrait
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

    public function testExceptionOnInvalidAttributeKey()
    {
        $helper = new ErrorList(new ErrorFormatter());
        $this->expectException(InvalidHtmlAttributeKeyException::class);
        $helper(
            new FormErrorSequence(new FormError('', 'error.required')),
            [1 => 'test']
        );
    }

    public function testExceptionOnInvalidAttributeValue()
    {
        $helper = new ErrorList(new ErrorFormatter());
        $this->expectException(InvalidHtmlAttributeValueException::class);
        $helper(
            new FormErrorSequence(new FormError('', 'error.required')),
            ['test' => 1]
        );
    }
}
