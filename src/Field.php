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
    private $fieldValue;

    /**
     * @var ValidationErrors
     */
    private $fieldErrors;

    public function __construct(string $key, string $fieldValue, FormErrorSequence $fieldErrors)
    {
        $this->key = $key;
        $this->fieldValue = $fieldValue;
        $this->fieldErrors = $fieldErrors;
    }

    public function getKey() : string
    {
        return $this->key;
    }

    public function getFieldValue() : string
    {
        return $this->fieldValue;
    }

    public function getFieldErrors() : FormErrorSequence
    {
        return $this->fieldErrors;
    }
}
