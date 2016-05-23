<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest;

use DASPRiD\Formidable\Field;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Field
 */
class FieldTest extends TestCase
{
    public function testKeyRetrieval()
    {
        $field = new Field('foo', '', new FormErrorSequence());
        $this->assertSame('foo', $field->getKey());
    }

    public function testValueRetrieval()
    {
        $field = new Field('', 'foo', new FormErrorSequence());
        $this->assertSame('foo', $field->getValue());
    }

    public function testErrorRetrieval()
    {
        $errors = new FormErrorSequence();
        $field = new Field('', '', $errors);
        $this->assertSame($errors, $field->getErrors());
    }
}
