<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping;

use Assert\AssertionFailedException;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\Mapping\BindResult;
use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;
use DASPRiD\Formidable\Mapping\Constraint\ValidationError;
use DASPRiD\Formidable\Mapping\Constraint\ValidationResult;
use DASPRiD\Formidable\Mapping\MappingInterface;
use DASPRiD\Formidable\Mapping\ObjectMapping;
use DASPRiD\FormidableTest\Mapping\TestAsset\SimpleObject;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use stdClass;

/**
 * @covers DASPRiD\Formidable\Mapping\ObjectMapping
 * @covers DASPRiD\Formidable\Mapping\MappingTrait
 */
class ObjectMappingTest extends TestCase
{
    use MappingTraitTestTrait;

    public function testConstructionWithInvalidMappingKey()
    {
        $this->expectException(AssertionFailedException::class);
        return new ObjectMapping([1 => $this->prophesize(MappingInterface::class)->reveal()], stdClass::class);
    }

    public function testConstructionWithInvalidMapping()
    {
        $this->expectException(AssertionFailedException::class);
        return new ObjectMapping(['foo' => 'bar'], stdClass::class);
    }

    public function testConstructionWithNonExistentClassName()
    {
        $this->expectException(AssertionFailedException::class);
        return new ObjectMapping([], 'DASPRiD\FormidableTest\Mapping\NonExistentClassName');
    }

    public function testUnbindNonMatchingClass()
    {
        $mapping = (new ObjectMapping([], stdClass::class));
        $this->expectException(AssertionFailedException::class);
        $mapping->unbind('foo');
    }

    public function testBindValidData()
    {
        $data = Data::fromFlatArray(['foo' => 'baz', 'bar' => 'bat']);
        $objectMapping = new ObjectMapping([
            'foo' => $this->getMockedMapping('foo', 'baz', $data),
            'bar' => $this->getMockedMapping('bar', 'bat', $data),
        ], SimpleObject::class);

        $bindResult = $objectMapping->bind($data);
        $this->assertTrue($bindResult->isSuccess());
        $this->assertInstanceOf(SimpleObject::class, $bindResult->getValue());
        $this->assertSame('baz', $bindResult->getValue()->getFoo());
        $this->assertSame('bat', $bindResult->getValue()->getBar());
    }

    public function testBindInvalidData()
    {
        $data = Data::fromFlatArray(['foo' => 'baz', 'bar' => 'bat']);
        $objectMapping = new ObjectMapping([
            'foo' => $this->getMockedMapping('foo', 'baz', $data),
            'bar' => $this->getMockedMapping('bar', 'bat', $data, false),
        ], SimpleObject::class);

        $bindResult = $objectMapping->bind($data);
        $this->assertFalse($bindResult->isSuccess());
        $this->assertSame('bat', iterator_to_array($bindResult->getFormErrorSequence())[0]->getMessage());
    }

    public function testBindAppliesConstraints()
    {
        $constraint = $this->prophesize(ConstraintInterface::class);
        $constraint->__invoke(Argument::type(SimpleObject::class))->willReturn(new ValidationResult(
            new ValidationError('error', [], 'foo[bar]')
        ));

        $data = Data::fromFlatArray(['foo' => 'baz', 'bar' => 'bat']);
        $objectMapping = (new ObjectMapping([
            'foo' => $this->getMockedMapping('foo', 'baz', $data),
            'bar' => $this->getMockedMapping('bar', 'bat', $data),
        ], SimpleObject::class))->verifying($constraint->reveal());

        $bindResult = $objectMapping->bind($data);
        $this->assertFalse($bindResult->isSuccess());
        $formError = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('error', $formError->getMessage());
        $this->assertSame('foo[bar]', $formError->getKey());
    }

    public function testUnbindObject()
    {
        $objectMapping = new ObjectMapping([
            'foo' => $this->getMockedMapping('foo', 'baz'),
            'bar' => $this->getMockedMapping('bar', 'bat'),
        ], SimpleObject::class);

        $data = $objectMapping->unbind(new SimpleObject('baz', 'bat'));
        $this->assertSame('baz', $data->getValue('foo'));
        $this->assertSame('bat', $data->getValue('bar'));
    }

    public function testUnbindObjectWithMissingProperty()
    {
        $objectMapping = new ObjectMapping([
            'foo' => $this->getMockedMapping('foo', 'baz'),
            'bar' => $this->getMockedMapping('bar', 'bat'),
            'none' => $this->getMockedMapping('none', 'none'),
        ], SimpleObject::class);

        $this->expectException(AssertionFailedException::class);
        $objectMapping->unbind(new SimpleObject('baz', 'bat'));
    }

    public function testInvalidUnapplyReturnValue()
    {
        $objectMapping = new ObjectMapping([], SimpleObject::class, null, function () {
            return null;
        });
        $this->expectException(AssertionFailedException::class);
        $objectMapping->unbind(new SimpleObject('', ''));
    }

    public function testCreatePrefixedKey()
    {
        $objectMapping = (new ObjectMapping([], stdClass::class))->withPrefixAndRelativeKey('foo', 'bar');
        $this->assertAttributeSame('foo[bar]', 'key', $objectMapping);
    }

    public function testKeyCloneCreatesNewMapings()
    {
        $mapping = $this->prophesize(MappingInterface::class);
        $mapping->withPrefixAndRelativeKey('foo', 'bar')->shouldBeCalled()->willReturn($mapping->reveal());
        $mapping->withPrefixAndRelativeKey('', 'bar')->shouldBeCalled()->willReturn($mapping->reveal());

        (new ObjectMapping([
            'bar' => $mapping->reveal(),
        ], stdClass::class))->withPrefixAndRelativeKey('', 'foo');
    }

    /**
     * {@inheritdoc}
     */
    protected function getInstanceForTraitTests() : MappingInterface
    {
        return new ObjectMapping([], stdClass::class);
    }

    private function getMockedMapping(string $key, string $value, Data $data = null, $success = true) : MappingInterface
    {
        $mapping = $this->prophesize(MappingInterface::class);
        $mapping->unbind($value)->willReturn(Data::fromFlatArray([$key => $value]));

        if (null !== $data) {
            if ($success) {
                $mapping->bind($data)->willReturn(BindResult::fromValue($value));
            } else {
                $mapping->bind($data)->willReturn(BindResult::fromFormErrors(new FormError($key, $value)));
            }
        }

        $mapping->withPrefixAndRelativeKey('', $key)->willReturn($mapping->reveal());

        return $mapping->reveal();
    }
}
