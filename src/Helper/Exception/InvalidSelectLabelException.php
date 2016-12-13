<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper\Exception;

use DomainException;

final class InvalidSelectLabelException extends DomainException implements ExceptionInterface
{
    public static function fromInvalidLabel($label) : self
    {
        return new self(sprintf(
            'Label must either be a string or an array of child values, but got %s',
            gettype($label)
        ));
    }
}
