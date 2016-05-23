<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

use DASPRiD\Formidable\FormError\FormErrorSequence;

final class Field
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * @var FormErrorSequence
     */
    private $errors;

    public function __construct(string $key, string $value, FormErrorSequence $errors)
    {
        $this->key = $key;
        $this->value = $value;
        $this->errors = $errors;
    }

    public function getKey() : string
    {
        return $this->key;
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public function getErrors() : FormErrorSequence
    {
        return $this->errors;
    }

    public function hasErrors() : bool
    {
        return !$this->errors->isEmpty();
    }
}
