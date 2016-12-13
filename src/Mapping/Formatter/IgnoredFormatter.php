<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\BindResult;

final class IgnoredFormatter implements FormatterInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : BindResult
    {
        return BindResult::fromValue($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        return Data::none();
    }
}
