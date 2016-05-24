<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper\Exception;

use OutOfBoundsException;

class NonExistentMessage extends OutOfBoundsException implements ExceptionInterface
{
    public static function fromNonExistentMessageKey(string $key)
    {
        return new self(sprintf('Non-existent message key "%s" provided', $key));
    }
}
