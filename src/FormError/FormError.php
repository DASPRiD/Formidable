<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\FormError;

final class FormError
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $arguments;

    public function __construct(string $key, string $message, array $arguments = [])
    {
        $this->key = $key;
        $this->message = $message;
        $this->arguments = $arguments;
    }

    public function getKey() : string
    {
        return $this->key;
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
