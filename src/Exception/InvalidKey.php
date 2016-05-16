<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Exception;

use DomainException;

class InvalidKey extends DomainException implements ExceptionInterface
{
    public static function fromArrayWithNonStringKeys(array $array)
    {
        return new self('Non-string key in array found');
    }

    public static function fromNonNestedKey($key)
    {
        return new self(sprintf('Expected string or nested integer key, but "%s" was provided', gettype($key)));
    }
}
