<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Exception;

use DomainException;

class InvalidValue extends DomainException implements ExceptionInterface
{
    public static function fromArrayWithNonStringValues(array $array)
    {
        return new self('Non-string value in array found');
    }

    public static function fromNonNestedValue($value)
    {
        return new self(sprintf('Expected string or array value, but "%s" provided', gettype($value)));
    }
}
