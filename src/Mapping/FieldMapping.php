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
    use MappingTrait;

    /**
     * @var FormatterInterface
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

    public function bind(Data $data) : BindResult
    {
        $this->binder->bind($this->key, $data);
    }

    public function unbind($value) : Data
    {
        return $this->binder->unbind($this->key, $value);
    }

    public function withPrefixAndRelativeKey(string $prefix, string $relativeKey) : MappingInterface
    {
        $clone = clone $this;
        $clone->key = $this->createKeyFromPrefixAndRelativeKey($prefix, $relativeKey);
        return $clone;
    }
}
