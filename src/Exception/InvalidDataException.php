<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Exception;

use DomainException;

final class InvalidDataException extends DomainException implements ExceptionInterface
{
    public static function fromGetValueAttempt() : self
    {
        return new self('Value cannot be retrieved when the form has errors');
    }
}
