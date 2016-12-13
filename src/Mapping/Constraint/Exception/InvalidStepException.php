<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint\Exception;

use DomainException;
use Litipk\BigNumbers\Decimal;

final class InvalidStepException extends DomainException implements ExceptionInterface
{
    public static function fromNonNumericStep($step) : self
    {
        return new self(sprintf(
            'Step was expected to be numeric, but got %s',
            is_string($step) ? sprintf('"%s"', $step) : gettype($step)
        ));
    }

    public static function fromNonNumericBase($base) : self
    {
        return new self(sprintf(
            'Base was expected to be numeric, but got %s',
            is_string($base) ? sprintf('"%s"', $base) : gettype($base)
        ));
    }

    public static function fromZeroOrNegativeStep(Decimal $step) : self
    {
        return new self(sprintf(
            'Step must be greater than zero, but got %s',
            (string) $step
        ));
    }
}
