<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper\Exception;

use RuntimeException;

final class MissingIntlExtensionException extends RuntimeException implements ExceptionInterface
{
    public static function fromMissingExtension() : self
    {
        return new self('You must install the PHP intl extension for this helper to work');
    }
}
