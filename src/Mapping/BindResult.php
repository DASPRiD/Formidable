<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use Assert\Assertion;
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
    private $formErrors;

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

    public static function fromFormErrors(FormErrorSequence $formErrors)
    {
        $bindResult = new self();
        $bindResult->formErrors = $formErrors;
        return $bindResult;
    }

    public function isSuccess()
    {
        return null === $this->formErrors;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        Assertion::same(null, $this->formErrors, 'Value can only be retrieved when bind result was successful');
        return $this->value;
    }

    public function getFormErrors() : FormErrorSequence
    {
        Assertion::notNull($this->formErrors, 'Form errors can only be retrieved when bind result was not successful');
        return $this->formErrors;
    }
}
