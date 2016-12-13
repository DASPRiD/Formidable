<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Exception;

use DomainException;

final class UnboundDataException extends DomainException implements ExceptionInterface
{
    public static function fromGetValueAttempt() : self
    {
        return new self('No data have been bound to the form');
    }
}
