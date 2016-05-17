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

    public function __construct(string $message, array $arguments = [])
    {
        $this->message = $message;
        $this->arguments = $arguments;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getArguments() : array
    {
        return $this->arguments;
    }
}
