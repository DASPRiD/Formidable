<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Field;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Field
 */
class FieldTest extends TestCase
{
    public function testKeyRetrieval()
    {
        $field = new Field('foo', '', new FormErrorSequence(), Data::none());
        $this->assertSame('foo', $field->getKey());
    }

    public function testValueRetrieval()
    {
        $field = new Field('', 'foo', new FormErrorSequence(), Data::none());
        $this->assertSame('foo', $field->getValue());
    }

    public function testErrorRetrieval()
    {
        $errors = new FormErrorSequence();
        $field = new Field('', '', $errors, Data::none());
        $this->assertSame($errors, $field->getErrors());
    }

    public function testHasErrorsReturnsFalseWithoutErrors()
    {
        $errors = new FormErrorSequence();
        $field = new Field('', '', $errors, Data::none());
        $this->assertFalse($field->hasErrors());
    }

    public function testHasErrorsReturnsTrueWithErrors()
    {
        $errors = new FormErrorSequence(new FormError('', ''));
        $field = new Field('', '', $errors, Data::none());
        $this->assertTrue($field->hasErrors());
    }

    public function testGetIndexes()
    {
        $field = new Field('foo', '', new FormErrorSequence(), Data::fromFlatArray([
            'foo[0]' => 'bar0',
            'foo[1]' => 'bar1',
            'foo[2][baz]' => 'bar2',
        ]));

        $this->assertSame(['0', '1', '2'], $field->getIndexes());
    }

    public function testGetNestedValues()
    {
        $field = new Field('foo', '', new FormErrorSequence(), Data::fromFlatArray([
            'foo[0]' => 'bar0',
            'foo[1]' => 'bar1',
            'foo[1][baz]' => 'bar2',
        ]));

        $this->assertSame(['bar0', 'bar1'], $field->getNestedValues());
    }

    public function testGetNestedValuesPreserveKeys()
    {
        $field = new Field('foo', '', new FormErrorSequence(), Data::fromFlatArray([
            'foo[bar]' => 'bar0',
            'foo[baz]' => 'baz0',
            'foo[1][baz]' => 'bar2',
        ]));

        $this->assertSame([
            'bar' => 'bar0',
            'baz' => 'baz0'
        ], $field->getNestedValues(true));
    }

    public function testGetNestedValuesNoPreserveKeys()
    {
        $field = new Field('foo', '', new FormErrorSequence(), Data::fromFlatArray([
            'foo[bar]' => 'bar0',
            'foo[baz]' => 'baz0',
            'foo[1][baz]' => 'bar2',
        ]));

        $this->assertSame(['bar0', 'baz0'], $field->getNestedValues());
    }
}
