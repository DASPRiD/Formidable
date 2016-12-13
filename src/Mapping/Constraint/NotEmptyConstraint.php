<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidTypeException;

class NotEmptyConstraint implements ConstraintInterface
{
    public function __invoke($value) : ValidationResult
    {
        if (!is_string($value)) {
            throw InvalidTypeException::fromInvalidType($value, 'string');
        }

        if ('' === $value) {
            return new ValidationResult(new ValidationError('error.empty'));
        }

        return new ValidationResult();
    }
}
