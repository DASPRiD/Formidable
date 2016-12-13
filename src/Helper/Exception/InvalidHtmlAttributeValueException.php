<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper\Exception;

use DomainException;

final class InvalidHtmlAttributeValueException extends DomainException implements ExceptionInterface
{
    public static function fromInvalidValue($value) : self
    {
        return new self(sprintf('HTML attribute value must be of type string, but got %s', gettype($value)));
    }
}
