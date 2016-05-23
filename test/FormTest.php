<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest;

use DASPRiD\Formidable\Form;
use DASPRiD\Formidable\Mapping\MappingInterface;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Form
 */
class FormTest extends TestCase
{
    public function testFieldRetrivalFromUnknownField()
    {
        $form = new Form($this->prophesize(MappingInterface::class)->reveal());
        $field = $form->getField('foo');

        $this->assertSame('foo', $field->getKey());
        $this->assertSame('', $field->getValue());
        $this->assertTrue($field->getErrors()->isEmpty());
    }
}
