<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint\Exception;

use DomainException;

final class InvalidLimitException extends DomainException implements ExceptionInterface
{
    public static function fromNonNumericValue($actualValue) : self
    {
        return new self(sprintf(
            'Limit was expected to be numeric, but got %s',
            is_string($actualValue) ? sprintf('"%s"', $actualValue) : gettype($actualValue)
        ));
    }
}
