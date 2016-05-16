<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Data;
use ReflectionClass;

final class RepeatedMapping implements MappingInterface
{
    use MappingTrait;

    /**
     * @var MappingInterface
     */
    private $wrappedMapping;

    /**
     * @var string
     */
    private $key = '';

    /**
     * @param MappingInterface $wrappedMapping
     */
    public function __construct(MappingInterface $wrappedMapping)
    {
        $this->wrappedMapping = $wrappedMapping;
    }

    public function bind(Data $data)
    {
        $values = [];

        foreach ($data->getIndexes($this->key) as $index) {
            $values[] = $this->wrappedMapping
                ->withPrefixAndRelativeKey($this->key . '[' . $index . ']')
                ->bind($data);
        }

        return $values;
    }

    public function unbind($value) : Data
    {
        if (!is_array($value)) {
            // @todo throw exception
        }

        $data = new Data([]);

        foreach ($value as $individualValue) {
            $data = $data->merge(
                $this->wrappedMapping
                ->withPrefixAndRelativeKey($this->key . '[]')
                ->unbind($individualValue)
            );
        }

        return $data;
    }

    public function withPrefixAndRelativeKey(string $prefix, string $relativeKey) : MappingInterface
    {
        $clone = clone $this;
        $clone->key = $this->createKeyFromPrefixAndRelativeKey($prefix, $relativeKey);
        return $clone;
    }
}
