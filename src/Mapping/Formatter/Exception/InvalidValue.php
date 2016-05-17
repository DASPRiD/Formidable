<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter\Exception;

use DomainException;

class InvalidValue extends DomainException implements ExceptionInterface
{
    public static function fromNonBoolean($value)
    {
        return new self(sprintf('Expected boolean, but "%s" was provided', gettype($value)));
    }

    public static function fromNonFloat($value)
    {
        return new self(sprintf('Expected float, but "%s" was provided', gettype($value)));
    }

    public static function fromNonInteger($value)
    {
        return new self(sprintf('Expected integer, but "%s" was provided', gettype($value)));
    }

    public static function fromNonString($value)
    {
        return new self(sprintf('Expected string, but "%s" was provided', gettype($value)));
    }
}
