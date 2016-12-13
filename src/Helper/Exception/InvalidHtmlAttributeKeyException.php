<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper\Exception;

use DomainException;

final class InvalidHtmlAttributeKeyException extends DomainException implements ExceptionInterface
{
    public static function fromInvalidKey($key) : self
    {
        return new self(sprintf('HTML attribute key must be of type string, but got %s', gettype($key)));
    }
}
