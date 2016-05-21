<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use Assert\Assertion;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;

final class BindResult
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var FormErrorSequence
     */
    private $formErrorSequence;

    private function __construct()
    {
    }

    /**
     * @param mixed $value
     */
    public static function fromValue($value) : self
    {
        $bindResult = new self();
        $bindResult->value = $value;
        return $bindResult;
    }

    public static function fromFormErrors(FormError ...$formErrors)
    {
        $bindResult = new self();
        $bindResult->formErrorSequence = new FormErrorSequence(...$formErrors);
        return $bindResult;
    }

    public static function fromFormErrorSequence(FormErrorSequence $formErrorSequence)
    {
        $bindResult = new self();
        $bindResult->formErrorSequence = $formErrorSequence;
        return $bindResult;
    }

    public function isSuccess()
    {
        return null === $this->formErrorSequence;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        Assertion::same(null, $this->formErrorSequence, 'Value can only be retrieved when bind result was successful');
        return $this->value;
    }

    public function getFormErrorSequence() : FormErrorSequence
    {
        Assertion::notNull(
            $this->formErrorSequence,
            'Form errors can only be retrieved when bind result was not successful'
        );
        return $this->formErrors;
    }
}
