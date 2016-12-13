<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use DomainException;
use Throwable;

final class BindFailureException extends DomainException implements ExceptionInterface
{
    use NestedMappingExceptionTrait;

    public static function fromBindException(string $mappingKey, Throwable $previous) : self
    {
        return self::fromException('bind', $mappingKey, $previous);
    }
}
