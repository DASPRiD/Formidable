<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use DASPRiD\Formidable\Mapping\MappingInterface;
use DomainException;

final class InvalidMappingException extends DomainException implements ExceptionInterface
{
    public static function fromInvalidMapping($mapping) : self
    {
        return new self(sprintf(
            'Mapping was expected to implement %s, but got %s',
            MappingInterface::class,
            is_object($mapping) ? get_class($mapping) : gettype($mapping)
        ));
    }
}
