<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

class ChainConstraint implements ConstraintInterface
{
    /**
     * @var bool
     */
    private $breakChainOnFailure;

    /**
     * @var ConstraintInterface[]
     */
    private $constraints;

    public function __construct(bool $breakChainOnFailure, ConstraintInterface ...$constraints)
    {
        $this->breakChainOnFailure = $breakChainOnFailure;
        $this->constraints = $constraints;
    }

    public function __invoke($value) : ValidationResult
    {
        $validationResult = new ValidationResult();

        foreach ($this->constraints as $constraint) {
            $childValidationResult = $constraint($value);

            if ($childValidationResult->isSuccess()) {
                continue;
            }

            if ($this->breakChainOnFailure) {
                return $childValidationResult;
            }

            $validationResult = $validationResult->merge($childValidationResult);
        }

        return $validationResult;
    }
}
