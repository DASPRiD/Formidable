<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use ArrayIterator;
use Traversable;

final class ValidationResult
{
    /**
     * @var ValidationError[]
     */
    private $validationErrors;

    public function __construct(ValidationError ...$validationErrors)
    {
        $this->validationErrors = $validationErrors;
    }

    public function isSuccess() : bool
    {
        return empty($this->validationErrors);
    }

    public function merge(self $other) : self
    {
        $validationResult = clone $this;
        $validationResult->validationErrors = array_merge($this->validationErrors, $other->validationErrors);
        return $validationResult;
    }

    /**
     * @return ValidationError[]
     */
    public function getValidationErrors() : Traversable
    {
        return new ArrayIterator($this->validationErrors);
    }
}
