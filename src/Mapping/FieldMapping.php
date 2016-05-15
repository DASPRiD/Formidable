<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Formatter\BooleanFormatter;
use DASPRiD\Formidable\Mapping\Formatter\FormatterInterface;
use DASPRiD\Formidable\Mapping\Formatter\IntegerFormatter;
use DASPRiD\Formidable\Mapping\Formatter\TextFormatter;

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

    public function bind(Data $data)
    {
        $this->binder->bind($data);
    }

    public function unbind($value) : Data
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
