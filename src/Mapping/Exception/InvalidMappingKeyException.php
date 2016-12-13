<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use DomainException;

final class InvalidMappingKeyException extends DomainException implements ExceptionInterface
{
    public static function fromInvalidMappingKey($mappingKey) : self
    {
        return new self(sprintf('Mapping key must be of type string, but got %s', gettype($mappingKey)));
    }
}
