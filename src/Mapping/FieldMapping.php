<?php
declare(strict_types = 1);

namespace DASPRiD\SimpleForm\Mapping;

use DASPRiD\SimpleForm\Mapping\Formatter\FormatterInterface;
use DASPRiD\SimpleForm\Mapping\Formatter\TextFormatter;

final class FieldMapping implements MappingInterface
{
    /**
     * @var Formatter
     */
    private $binder;

    /**
     * @var string
     */
    private $key = '';

    public function __construct(FormatterInterface $binder)
    {
        $this->binder = $binder;
    }

    public static function text() : self
    {
        return new self(new TextFormatter());
    }

    public static function integer() : self
    {
        return new self(new IntegerFormatter());
    }

    public static function boolean() : self
    {
        return new self(new BooleanFormatter());
    }

    public function bind(array $data)
    {
    }

    public function unbind($value) : array
    {
        return $this->binder->unbind($this->key, $value);
    }

    public function withPrefix(string $prefix) : self
    {
        $fieldMapping = clone $this;
        $fieldMapping->key = $prefix . $fieldMapping->key;

        return $fieldMapping;
    }
}
