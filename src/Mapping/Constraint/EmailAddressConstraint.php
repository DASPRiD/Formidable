<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use Assert\Assertion;

class EmailAddressConstraint implements ConstraintInterface
{
    public function __invoke($value) : ValidationResult
    {
        Assertion::string($value);

        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return new ValidationResult(new ValidationError('error.email'));
        }

        return new ValidationResult();
    }
}
