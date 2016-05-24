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

    /**
     * @var Data
     */
    private $data;

    public function __construct(string $key, string $value, FormErrorSequence $errors, Data $data)
    {
        $this->key = $key;
        $this->value = $value;
        $this->errors = $errors;
        $this->data = $data;
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

    /**
     * @return array[]
     */
    public function getNestedValues() : array
    {
        $values = [];

        foreach ($this->data->getIndexes($this->key) as $index) {
            $key = $this->key . '[' . $index . ']';

            if ($this->data->hasKey($key)) {
                $values[] = $this->data->getValue($key);
            }
        }

        return $values;
    }
}
