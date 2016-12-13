<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint\Exception;

use DomainException;

final class InvalidLengthException extends DomainException implements ExceptionInterface
{
    public static function fromNegativeLength(int $length) : self
    {
        return new self(sprintf(
            'Length must be greater than or equal to zero, but got %d',
            $length
        ));
    }
}
