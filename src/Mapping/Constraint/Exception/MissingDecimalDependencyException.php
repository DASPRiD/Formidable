<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint\Exception;

use RuntimeException;

final class MissingDecimalDependencyException extends RuntimeException implements ExceptionInterface
{
    public static function fromMissingDependency() : self
    {
        return new self('You must composer require litipk/php-bignumbers for this constraint to work');
    }
}
