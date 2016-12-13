<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint\Exception;

use DomainException;

final class InvalidTypeException extends DomainException implements ExceptionInterface
{
    public static function fromInvalidType($actualValue, string $expectedType) : self
    {
        return new self(sprintf(
            'Value was expected to be of type %s, but got %s',
            $expectedType,
            is_object($actualValue) ? get_class($actualValue) : gettype($actualValue)
        ));
    }

    public static function fromNonNumericValue($actualValue) : self
    {
        return new self(sprintf(
            'Value was expected to be numeric, but got %s',
            is_string($actualValue) ? sprintf('"%s"', $actualValue) : gettype($actualValue)
        ));
    }
}
