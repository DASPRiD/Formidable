<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use DomainException;

final class InvalidBindResultException extends DomainException implements ExceptionInterface
{
    public static function fromGetValueAttempt() : self
    {
        return new self('Value can only be retrieved when bind result was successful');
    }
}
