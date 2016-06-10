<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use Assert\Assertion;

class UrlConstraint implements ConstraintInterface
{
    public function __invoke($value) : ValidationResult
    {
        Assertion::string($value);

        if (false === filter_var($value, FILTER_VALIDATE_URL)) {
            return new ValidationResult(new ValidationError('error.url'));
        }

        return new ValidationResult();
    }
}
