<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Exception;

use OutOfBoundsException;

final class NonExistentUnapplyKeyException extends OutOfBoundsException implements ExceptionInterface
{
    public static function fromNonExistentUnapplyKey(string $key) : self
    {
        return new self(sprintf('Key "%s" not found in array returned by unapply function', $key));
    }
}
