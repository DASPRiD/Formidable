<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Mapping\Constraint\EmailAddressConstraint;
use DASPRiD\Formidable\Mapping\Constraint\MaxLengthConstraint;
use DASPRiD\Formidable\Mapping\Constraint\MaxNumberConstraint;
use DASPRiD\Formidable\Mapping\Constraint\MinLengthConstraint;
use DASPRiD\Formidable\Mapping\Constraint\MinNumberConstraint;
use DASPRiD\Formidable\Mapping\Constraint\StepNumberConstraint;
use DASPRiD\Formidable\Mapping\Constraint\UrlConstraint;
use DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter;
use DASPRiD\Formidable\Mapping\Formatter\DateFormatter;
use DASPRiD\Formidable\Mapping\Formatter\DateTimeFormatter;
use DASPRiD\Formidable\Mapping\Formatter\DecimalFormatter;
use DASPRiD\Formidable\Mapping\Formatter\FloatFormatter;
use DASPRiD\Formidable\Mapping\Formatter\IntegerFormatter;
use DASPRiD\Formidable\Mapping\Formatter\TextFormatter;
use DASPRiD\Formidable\Mapping\Formatter\TimeFormatter;
use DateTimeZone;

final class FieldMappingFactory
{
    /**
     * @var DateTimeZone
     */
    private static $utcTimeZone;

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function text(int $minLength = 0, int $maxLength = null, $encoding = 'utf-8') : FieldMapping
    {
        $mapping = new FieldMapping(new TextFormatter());

        if ($minLength > 0) {
            $mapping = $mapping->verifying(new MinLengthConstraint($minLength, $encoding));
        }

        if (null !== $maxLength) {
            $mapping = $mapping->verifying(new MaxLengthConstraint($maxLength, $encoding));
        }

        return $mapping;
    }

    public static function emailAddress() : FieldMapping
    {
        return self::text()->verifying(new EmailAddressConstraint());
    }

    public static function url() : FieldMapping
    {
        return self::text()->verifying(new UrlConstraint());
    }

    public static function integer(int $min = null, int $max = null, int $step = null) : FieldMapping
    {
        return self::addNumberConstraints(new FieldMapping(new IntegerFormatter()), $min, $max, $step);
    }

    public static function float(float $min = null, float $max = null, float $step = null) : FieldMapping
    {
        return self::addNumberConstraints(new FieldMapping(new FloatFormatter()), $min, $max, $step);
    }

    public static function decimal(string $min = null, string $max = null, string $step = null) : FieldMapping
    {
        return self::addNumberConstraints(new FieldMapping(new DecimalFormatter()), $min, $max, $step);
    }

    public static function boolean() : FieldMapping
    {
        return new FieldMapping(new BooleanFormatter());
    }

    public static function time(DateTimeZone $timeZone = null) : FieldMapping
    {
        return new FieldMapping(new TimeFormatter($timeZone ?: self::getUtcTimeZone()));
    }

    public static function date(DateTimeZone $timeZone = null) : FieldMapping
    {
        return new FieldMapping(new DateFormatter($timeZone ?: self::getUtcTimeZone()));
    }

    public static function dateTime(DateTimeZone $timeZone = null, $localTime = false) : FieldMapping
    {
        return new FieldMapping(new DateTimeFormatter($timeZone ?: self::getUtcTimeZone(), $localTime));
    }

    /**
     * @param int|float|string $min
     * @param int|float|string $max
     * @param int|float|string $step
     */
    private static function addNumberConstraints(FieldMapping $mapping, $min, $max, $step) : FieldMapping
    {
        if (null !== $min) {
            $mapping = $mapping->verifying(new MinNumberConstraint($min));
        }

        if (null !== $max) {
            $mapping = $mapping->verifying(new MaxNumberConstraint($max));
        }

        if (null !== $step) {
            $mapping = $mapping->verifying(new StepNumberConstraint($step, $min));
        }

        return $mapping;
    }

    private static function getUtcTimeZone() : DateTimeZone
    {
        if (null === self::$utcTimeZone) {
            self::$utcTimeZone = new DateTimeZone('UTC');
        }

        return self::$utcTimeZone;
    }
}
