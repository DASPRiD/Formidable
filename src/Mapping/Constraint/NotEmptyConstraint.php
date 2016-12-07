<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use Assert\Assertion;

class NotEmptyConstraint implements ConstraintInterface
{
    public function __invoke($value) : ValidationResult
    {
        Assertion::string($value);

        if ('' === $value) {
            return new ValidationResult(new ValidationError('error.empty'));
        }

        return new ValidationResult();
    }
}
