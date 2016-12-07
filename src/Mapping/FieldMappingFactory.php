<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

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
        $mapping = new FieldMapping(new Formatter\TextFormatter());

        if ($minLength > 0) {
            $mapping = $mapping->verifying(new Constraint\MinLengthConstraint($minLength, $encoding));
        }

        if (null !== $maxLength) {
            $mapping = $mapping->verifying(new Constraint\MaxLengthConstraint($maxLength, $encoding));
        }

        return $mapping;
    }

    public static function nonEmptyText(int $maxLength = null, $encoding = 'utf-8') : FieldMapping
    {
        $constraints = [new Constraint\NotEmptyConstraint()];

        if (null !== $maxLength) {
            $constraints[] = new Constraint\MaxLengthConstraint($maxLength, $encoding);
        }

        return self::text()->verifying(new Constraint\ChainConstraint(true, ...$constraints));
    }

    public static function emailAddress() : FieldMapping
    {
        return self::text()->verifying(new Constraint\EmailAddressConstraint());
    }

    public static function url() : FieldMapping
    {
        return self::text()->verifying(new Constraint\UrlConstraint());
    }

    public static function integer(int $min = null, int $max = null, int $step = null) : FieldMapping
    {
        return self::addNumberConstraints(new FieldMapping(new Formatter\IntegerFormatter()), $min, $max, $step);
    }

    public static function float(float $min = null, float $max = null, float $step = null) : FieldMapping
    {
        return self::addNumberConstraints(new FieldMapping(new Formatter\FloatFormatter()), $min, $max, $step);
    }

    public static function decimal(string $min = null, string $max = null, string $step = null) : FieldMapping
    {
        return self::addNumberConstraints(new FieldMapping(new Formatter\DecimalFormatter()), $min, $max, $step);
    }

    public static function boolean() : FieldMapping
    {
        return new FieldMapping(new Formatter\BooleanFormatter());
    }

    public static function time(DateTimeZone $timeZone = null) : FieldMapping
    {
        return new FieldMapping(new Formatter\TimeFormatter($timeZone ?: self::getUtcTimeZone()));
    }

    public static function date(DateTimeZone $timeZone = null) : FieldMapping
    {
        return new FieldMapping(new Formatter\DateFormatter($timeZone ?: self::getUtcTimeZone()));
    }

    public static function dateTime(DateTimeZone $timeZone = null, $localTime = false) : FieldMapping
    {
        return new FieldMapping(new Formatter\DateTimeFormatter($timeZone ?: self::getUtcTimeZone(), $localTime));
    }

    /**
     * @param int|float|string $min
     * @param int|float|string $max
     * @param int|float|string $step
     */
    private static function addNumberConstraints(FieldMapping $mapping, $min, $max, $step) : FieldMapping
    {
        if (null !== $min) {
            $mapping = $mapping->verifying(new Constraint\MinNumberConstraint($min));
        }

        if (null !== $max) {
            $mapping = $mapping->verifying(new Constraint\MaxNumberConstraint($max));
        }

        if (null !== $step) {
            $mapping = $mapping->verifying(new Constraint\StepNumberConstraint($step, $min));
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
