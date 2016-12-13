<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use DomainException;

final class MappedClassMismatchException extends DomainException implements ExceptionInterface
{
    public static function fromMismatchedClass(string $expectedClass, $value) : self
    {
        return new self(sprintf(
            'Value to bind or unbind must be an instance of %s, but got %s',
            $expectedClass,
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }
}
