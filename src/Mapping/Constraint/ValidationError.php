<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

final class ValidationError
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var string
     */
    private $keySuffix;

    public function __construct(string $message, array $arguments = [], string $keySuffix = '')
    {
        $this->message = $message;
        $this->arguments = $arguments;
        $this->keySuffix = $keySuffix;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getArguments() : array
    {
        return $this->arguments;
    }

    public function getKeySuffix() : string
    {
        return $this->keySuffix;
    }
}
