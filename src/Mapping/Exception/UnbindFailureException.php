<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use DomainException;
use Throwable;

final class UnbindFailureException extends DomainException implements ExceptionInterface
{
    use NestedMappingExceptionTrait;

    public static function fromUnbindException(string $mappingKey, Throwable $previous) : self
    {
        return self::fromException('unbind', $mappingKey, $previous);
    }
}
