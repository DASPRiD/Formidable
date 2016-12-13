<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\DateTimeFormatter;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidTypeException;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DASPRiD\Formidable\Mapping\Formatter\DateTimeFormatter
 */
class DateTimeFormatterTest extends TestCase
{
    public function testBindTimeStringWithoutSeconds()
    {
        $this->assertSame('01:02:00.000000', (new DateTimeFormatter(new DateTimeZone('UTC')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '1970-01-01T01:02+00:00'])
        )->getValue()->format('H:i:s.u'));
    }

    public function testBindTimeStringWithSeconds()
    {
        $this->assertSame('01:02:03.000000', (new DateTimeFormatter(new DateTimeZone('UTC')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '1970-01-01T01:02:03+00:00'])
        )->getValue()->format('H:i:s.u'));
    }

    public function testBindTimeStringWithSecondsAndMicroseconds()
    {
        $this->assertSame('01:02:03.456789', (new DateTimeFormatter(new DateTimeZone('UTC')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '1970-01-01T01:02:03.456789+00:00'])
        )->getValue()->format('H:i:s.u'));
    }

    public function testBindToSpecificTimeZone()
    {
        $dateTime = (new DateTimeFormatter(new DateTimeZone('Europe/Berlin')))->bind(
            'foo',
            Data::fromFlatArray(['foo' => '1970-01-01T01:02:03+00:00'])
        )->getValue();

        $this->assertSame('Europe/Berlin', $dateTime->getTimezone()->getName());
        $this->assertSame('1970-01-01T02:02:03', $dateTime->format('Y-m-d\TH:i:s'));
    }

    public function testBindEmptyStringValue()
    {
        $bindResult = (new DateTimeFormatter(new DateTimeZone('UTC')))->bind('foo', Data::fromFlatArray(['foo' => '']));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.date-time', $error->getMessage());
    }

    public function testThrowErrorOnBindNonExistentKey()
    {
        $bindResult = (new DateTimeFormatter(new DateTimeZone('UTC')))->bind('foo', Data::fromFlatArray([]));
        $this->assertFalse($bindResult->isSuccess());
        $this->assertCount(1, $bindResult->getFormErrorSequence());

        $error = iterator_to_array($bindResult->getFormErrorSequence())[0];
        $this->assertSame('foo', $error->getKey());
        $this->assertSame('error.required', $error->getMessage());
    }

    public function testUnbindDateTimeWithSeconds()
    {
        $data = (
            new DateTimeFormatter(new DateTimeZone('UTC'))
        )->unbind('foo', new DateTimeImmutable('1970-01-01 01:02:03 UTC'));
        $this->assertSame('1970-01-01T01:02:03+00:00', $data->getValue('foo'));
    }

    public function testUnbindDateTimeWithSecondsAndMicroseconds()
    {
        $data = (
            new DateTimeFormatter(new DateTimeZone('UTC'))
        )->unbind('foo', new DateTimeImmutable('1970-01-01 01:02:03.456789 UTC'));
        $this->assertSame('1970-01-01T01:02:03.456789+00:00', $data->getValue('foo'));
    }

    public function testUnbindDateTimeWithDifferentTimeZone()
    {
        $data = (new DateTimeFormatter(new DateTimeZone('UTC')))->unbind('foo', new DateTimeImmutable(
            '1970-01-01 01:02:03.456789',
            new DateTimeZone('Europe/Berlin')
        ));
        $this->assertSame('1970-01-01T00:02:03.456789+00:00', $data->getValue('foo'));
    }

    public function testUnbindInvalidStringValue()
    {
        $this->expectException(InvalidTypeException::class);
        (new DateTimeFormatter(new DateTimeZone('UTC')))->unbind('foo', '00:00:00');
    }
}
