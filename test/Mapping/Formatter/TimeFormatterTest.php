<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use Assert\AssertionFailedException;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\TimeFormatter;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\TimeFormatter
 */
class TimeFormatterTest extends TestCase
{
    public function testBindTimeStringWithoutSeconds()
    {
        $this->assertSame('01:02:00.000000', (new TimeFormatter(new DateTimeZone('UTC')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '01:02'])
        )->getValue()->format('H:i:s.u'));
    }

    public function testBindTimeStringWithSeconds()
    {
        $this->assertSame('01:02:03.000000', (new TimeFormatter(new DateTimeZone('UTC')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '01:02:03'])
        )->getValue()->format('H:i:s.u'));
    }

    public function testBindTimeStringWithSecondsAndMicroseconds()
    {
        $this->assertSame('01:02:03.456789', (new TimeFormatter(new DateTimeZone('UTC')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '01:02:03.456789'])
        )->getValue()->format('H:i:s.u'));
    }

    public function testBindToSpecificTimeZone()
    {
        $this->assertSame('Europe/Berlin', (new TimeFormatter(new DateTimeZone('Europe/Berlin')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '01:02:03'])
        )->getValue()->getTimezone()->getName());
    }

    public function testBindEmptyStringValue()
    {
        $bindResult = (new TimeFormatter(new DateTimeZone('UTC')))->bind('foo', Data::fromFlatArray(['foo' => '']));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.time', $error->getMessage());
    }

    public function testThrowErrorOnBindNonExistentKey()
    {
        $bindResult = (new TimeFormatter(new DateTimeZone('UTC')))->bind('foo', Data::fromFlatArray([]));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.required', $error->getMessage());
    }

    public function testUnbindDateTimeWithSeconds()
    {
        $data = (
            new TimeFormatter(new DateTimeZone('UTC'))
        )->unbind('foo', new DateTimeImmutable('1970-01-01 01:02:03 UTC'));
        $this->assertSame('01:02:03', $data->getValue('foo'));
    }

    public function testUnbindDateTimeWithSecondsAndMicroseconds()
    {
        $data = (
            new TimeFormatter(new DateTimeZone('UTC'))
        )->unbind('foo', new DateTimeImmutable('1970-01-01 01:02:03.456789 UTC'));
        $this->assertSame('01:02:03.456789', $data->getValue('foo'));
    }

    public function testUnbindDateTimeWithDifferentTimeZone()
    {
        $data = (new TimeFormatter(new DateTimeZone('UTC')))->unbind('foo', new DateTimeImmutable(
            '1970-01-01 01:02:03.456789',
            new DateTimeZone('Europe/Berlin')
        ));
        $this->assertSame('00:02:03.456789', $data->getValue('foo'));
    }

    public function testUnbindInvalidStringValue()
    {
        $this->expectException(AssertionFailedException::class);
        (new TimeFormatter(new DateTimeZone('UTC')))->unbind('foo', '00:00:00');
    }
}
