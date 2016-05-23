<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping;

use DASPRiD\Formidable\Mapping\Constraint\EmailAddressConstraint;
use DASPRiD\Formidable\Mapping\FieldMapping;
use DASPRiD\Formidable\Mapping\FieldMappingFactory;
use DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter;
use DASPRiD\Formidable\Mapping\Formatter\FloatFormatter;
use DASPRiD\Formidable\Mapping\Formatter\IntegerFormatter;
use DASPRiD\Formidable\Mapping\Formatter\TextFormatter;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\FieldMappingFactory
 */
class FieldMappingFactoryTest extends TestCase
{
    public function testTextFactoryWithoutConstraints()
    {
        $fieldMapping = FieldMappingFactory::text();
        $this->assertInstanceOf(FieldMapping::class, $fieldMapping);
        $this->assertAttributeInstanceOf(TextFormatter::class, 'binder', $fieldMapping);
        $this->assertAttributeCount(0, 'constraints', $fieldMapping);
    }

    public function testTextFactoryWithConstraints()
    {
        $fieldMapping = FieldMappingFactory::text(1, 2, 'iso-8859-15');
        $this->assertInstanceOf(FieldMapping::class, $fieldMapping);
        $this->assertAttributeInstanceOf(TextFormatter::class, 'binder', $fieldMapping);
        $this->assertAttributeCount(2, 'constraints', $fieldMapping);

        $constraints = self::readAttribute($fieldMapping, 'constraints');

        $this->assertAttributeSame('iso-8859-15', 'encoding', $constraints[0]);
        $this->assertAttributeSame(1, 'lengthLimit', $constraints[0]);

        $this->assertAttributeSame('iso-8859-15', 'encoding', $constraints[1]);
        $this->assertAttributeSame(2, 'lengthLimit', $constraints[1]);
    }

    public function testEmailAddressFactory()
    {
        $fieldMapping = FieldMappingFactory::emailAddress();
        $this->assertInstanceOf(FieldMapping::class, $fieldMapping);
        $this->assertAttributeInstanceOf(TextFormatter::class, 'binder', $fieldMapping);
        $this->assertAttributeCount(1, 'constraints', $fieldMapping);
        $this->assertInstanceOf(EmailAddressConstraint::class, self::readAttribute($fieldMapping, 'constraints')[0]);
    }

    public function testIntegerFactory()
    {
        $fieldMapping = FieldMappingFactory::integer();
        $this->assertInstanceOf(FieldMapping::class, $fieldMapping);
        $this->assertAttributeInstanceOf(IntegerFormatter::class, 'binder', $fieldMapping);
        $this->assertAttributeCount(0, 'constraints', $fieldMapping);
    }

    public function testFloatFactory()
    {
        $fieldMapping = FieldMappingFactory::float();
        $this->assertInstanceOf(FieldMapping::class, $fieldMapping);
        $this->assertAttributeInstanceOf(FloatFormatter::class, 'binder', $fieldMapping);
        $this->assertAttributeCount(0, 'constraints', $fieldMapping);
    }

    public function testBooleanFactory()
    {
        $fieldMapping = FieldMappingFactory::boolean();
        $this->assertInstanceOf(FieldMapping::class, $fieldMapping);
        $this->assertAttributeInstanceOf(BooleanFormatter::class, 'binder', $fieldMapping);
        $this->assertAttributeCount(0, 'constraints', $fieldMapping);
    }
}
