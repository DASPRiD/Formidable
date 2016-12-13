<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use UnexpectedValueException;

final class InvalidUnapplyResultException extends UnexpectedValueException implements ExceptionInterface
{
    public static function fromInvalidUnapplyResult($values) : self
    {
        return new self(sprintf(
            'Unapply was expected to return an array, but returned %s',
            gettype($values)
        ));
    }
}
