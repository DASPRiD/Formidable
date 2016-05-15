<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

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

    public function __construct(string $key, $fieldErrors, $fieldValue = null)
    {
        $this->key = $key;
        $this->fieldValue = $fieldValue;
    }

    public function hasValue() : bool
    {
        return null !== $this->fieldValue;
    }
}
