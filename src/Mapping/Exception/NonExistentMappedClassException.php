<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use DomainException;

final class NonExistentMappedClassException extends DomainException implements ExceptionInterface
{
    public static function fromNonExistentClass(string $className) : self
    {
        return new self(sprintf('Class with name %s does not exist', $className));
    }
}
