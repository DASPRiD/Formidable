<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

interface ConstraintInterface
{
    /**
     * @param mixed $value
     */
    public function __invoke($value) : ValidationResult;
}
