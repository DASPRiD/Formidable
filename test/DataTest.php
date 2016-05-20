<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Exception\InvalidKey;
use DASPRiD\Formidable\Exception\InvalidValue;
use DASPRiD\Formidable\Exception\NonExistentKey;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Data
 */
class DataTest extends TestCase
{
    public function testGetValueReturnsSetValue()
    {
        $data = Data::fromFlatArray(['foo' => 'bar']);
        $this->assertSame('bar', $data->getValue('foo'));
    }

    public function testGetValueReturnsFallbackWhenKeyDoesNotExist()
    {
        $data = Data::fromFlatArray([]);
        $this->assertSame('bar', $data->getValue('foo', 'bar'));
    }

    public function testGetValueThrowsExceptionWithoutFallback()
    {
        $this->expectException(NonExistentKey::class);
        $data = Data::fromFlatArray([]);
        $data->getValue('foo');
    }

    public function testHasKey()
    {
        $data = Data::fromFlatArray(['foo' => 'bar']);
        $this->assertTrue($data->hasKey('foo'));
        $this->assertFalse($data->hasKey('bar'));
    }

    public function testMerge()
    {
        $data1 = Data::fromFlatArray(['foo' => 'bar']);
        $data2 = Data::fromFlatArray(['foo' => 'baz', 'baz' => 'bat']);
        $mergedData = $data1->merge($data2);

        $this->assertSame('bar', $mergedData->getValue('foo'));
        $this->assertSame('bat', $mergedData->getValue('baz'));
    }

    public function testFilter()
    {
        $data = Data::fromFlatArray(['foo' => 'bar', 'baz' => 'bat'])->filter(function (string $value, string $key) {
            return $key === 'baz';
        });

        $this->assertFalse($data->hasKey('foo'));
        $this->assertTrue($data->hasKey('baz'));
    }

    public function testCreateFromFlatArrayWithInvalidKey()
    {
        $this->expectException(InvalidKey::class);
        Data::fromFlatArray([0 => 'foo']);
    }

    public function testCreateFromFlatArrayWithInvalidValue()
    {
        $this->expectException(InvalidValue::class);
        Data::fromFlatArray(['foo' => 0]);
    }

    public function testCreateFromNestedArray()
    {
        $data = Data::fromNestedArray([
            'foo' => [
                'bar' => ['baz', 'bat'],
            ]
        ]);

        $this->assertSame('baz', $data->getValue('foo[bar][0]'));
        $this->assertSame('bat', $data->getValue('foo[bar][1]'));
    }

    public function testCreateFromNestedArrayWithInvalidValue()
    {
        $this->expectException(InvalidValue::class);
        Data::fromNestedArray(['foo' => 1]);
    }

    public function testCreateFromNestedArrayWithRootIntegerKey()
    {
        $this->expectException(InvalidKey::class);
        Data::fromNestedArray([0 => 'foo']);
    }

    public function testCreateFromNestedArrayWithChildIntegerKey()
    {
        $data = Data::fromNestedArray(['foo' => [0 => 'bar']]);
        $this->assertSame('bar', $data->getValue('foo[0]'));
    }

    public function testCreateFromNestedArrayWithRootStringKey()
    {
        $data = Data::fromNestedArray(['foo' => 'bar']);
        $this->assertSame('bar', $data->getValue('foo'));
    }

    public function testCreateFromNestedArrayWithChildStringKey()
    {
        $data = Data::fromNestedArray(['foo' => ['bar' => 'baz']]);
        $this->assertSame('baz', $data->getValue('foo[bar]'));
    }

    public function testGetIndexes()
    {
        $data = Data::fromNestedArray([
            'foo' => [
                'bar',
                'baz' => 'bat',
                [
                    'foo',
                    'bar',
                ]
            ],
        ]);

        $this->assertSame(['0', 'baz', '1'], $data->getIndexes('foo'));
    }

    public function testIsEmptyReturnsTrueWithoutData()
    {
        $data = Data::fromFlatArray([]);
        $this->assertTrue($data->isEmpty());
    }

    public function testIsEmptyReturnsFalseWithData()
    {
        $data = Data::fromFlatArray(['foo' => 'bar']);
        $this->assertFalse($data->isEmpty());
    }
}
