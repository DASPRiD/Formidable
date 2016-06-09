<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;
use DASPRiD\Formidable\Mapping\Constraint\ValidationError;
use DASPRiD\Formidable\Mapping\Constraint\ValidationResult;

trait MappingTrait
{
    /**
     * @var ConstraintInterface[]
     */
    private $constraints = [];

    public function verifying(ConstraintInterface ...$constraints) : MappingInterface
    {
        $mapping = clone $this;
        $mapping->constraints = array_merge($this->constraints, $constraints);
        return $mapping;
    }

    protected function applyConstraints($value, string $key) : BindResult
    {
        $validationResult = new ValidationResult();

        foreach ($this->constraints as $constraint) {
            $validationResult = $validationResult->merge($constraint($value));
        }

        if ($validationResult->isSuccess()) {
            return BindResult::fromValue($value);
        }

        return BindResult::fromFormErrors(...array_map(
            function (ValidationError $validationError) use ($key) {
                if ('' === $key) {
                    $finalKey = $validationError->getKeySuffix();
                } elseif ('' === $validationError->getKeySuffix()) {
                    $finalKey = $key;
                } else {
                    $finalKey = $key . preg_replace('(^[^\[]+)', '[\0]', $validationError->getKeySuffix());
                }

                return new FormError(
                    $finalKey,
                    $validationError->getMessage(),
                    $validationError->getArguments()
                );
            },
            iterator_to_array($validationResult->getValidationErrors())
        ));
    }

    protected function createKeyFromPrefixAndRelativeKey(string $prefix, string $relativeKey) : string
    {
        if ('' === $prefix) {
            return $relativeKey;
        }

        return $prefix . '[' . $relativeKey . ']';
    }
}
