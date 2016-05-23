<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Mapping\Constraint\EmailAddressConstraint;
use DASPRiD\Formidable\Mapping\Constraint\MaxLengthConstraint;
use DASPRiD\Formidable\Mapping\Constraint\MinLengthConstraint;
use DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter;
use DASPRiD\Formidable\Mapping\Formatter\FloatFormatter;
use DASPRiD\Formidable\Mapping\Formatter\IntegerFormatter;
use DASPRiD\Formidable\Mapping\Formatter\TextFormatter;

final class FieldMappingFactory
{
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

    public static function integer() : FieldMapping
    {
        return new FieldMapping(new IntegerFormatter());
    }

    public static function float() : FieldMapping
    {
        return new FieldMapping(new FloatFormatter());
    }

    public static function boolean() : FieldMapping
    {
        return new FieldMapping(new BooleanFormatter());
    }
}
