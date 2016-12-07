<?php
namespace DASPRiD\Formidable\Mapping\Formatter;

class Utility
{

    public static function createStringAssertionExceptionMessage($value, string $key) : string
    {
        return sprintf(
            'Value "%s" in element "%s" expected to be type string. Type %s given.',
            self::stringify($value),
            $key,
            gettype($value)
        );
    }

    public static function createIntegerAssertionExceptionMessage($value, string $key) : string
    {
        return sprintf(
            'Value "%s" in element "%s" expected to be type integer. Type %s given.',
            self::stringify($value),
            $key,
            gettype($value)
        );
    }

    /**
     * @note this is a copy of Assert\Assertion::stringify which is protected
     */
    private static function stringify($value) : string
    {
        if (is_bool($value)) {
            return $value ? '<TRUE>' : '<FALSE>';
        }

        if (is_scalar($value)) {
            $val = (string)$value;

            if (strlen($val) > 100) {
                $val = substr($val, 0, 97) . '...';
            }

            return $val;
        }

        if (is_array($value)) {
            return '<ARRAY>';
        }

        if (is_object($value)) {
            return get_class($value);
        }

        if (is_resource($value)) {
            return '<RESOURCE>';
        }

        if ($value === null) {
            return '<NULL>';
        }

        // @codeCoverageIgnoreStart
        return 'unknown';
        // @codeCoverageIgnoreEnd
    }
}
