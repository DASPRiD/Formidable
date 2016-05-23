<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

use DASPRiD\Formidable\Exception\InvalidKey;
use DASPRiD\Formidable\Exception\InvalidValue;
use DASPRiD\Formidable\Exception\NonExistentKey;
use DASPRiD\Formidable\Transformer\TransformerInterface;

final class Data
{
    /**
     * @var array
     */
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @throws InvalidKey When a non-string key is encountered
     * @throws InvalidValue When a non-string value is encountered
     */
    public static function fromFlatArray(array $flatArray) : self
    {
        $originalCount = count($flatArray);

        if ($originalCount > count(array_filter($flatArray, 'is_string', ARRAY_FILTER_USE_KEY))) {
            throw InvalidKey::fromArrayWithNonStringKeys($flatArray);
        }

        if ($originalCount > count(array_filter($flatArray, 'is_string'))) {
            throw InvalidValue::fromArrayWithNonStringValues($flatArray);
        }

        return new self($flatArray);
    }

    public static function fromNestedArray(array $nestedArray) : self
    {
        return new self(self::flattenNestedArray($nestedArray));
    }

    public function merge(self $data) : self
    {
        $newData = clone $this;
        $newData->data = $newData->data + $data->data;

        return $newData;
    }

    public function filter(callable $filter) : self
    {
        $newData = clone $this;
        $newData->data = array_filter($newData->data, $filter, ARRAY_FILTER_USE_BOTH);

        return $newData;
    }

    public function transform(TransformerInterface $transformer) : self
    {
        $data = [];

        foreach ($this->data as $key => $value) {
            $data[$key] = $transformer($value, $key);
        }

        return new self($data);
    }

    public function hasKey(string $key) : bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @throws NonExistentKey When the key cannot be found and no fallback is defined
     */
    public function getValue(string $key, string $fallback = null) : string
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (null !== $fallback) {
            return $fallback;
        }

        throw NonExistentKey::fromNonExistentKey($key);
    }

    public function getIndexes(string $key) : array
    {
        return array_unique(
            array_reduce(
                array_keys($this->data),
                function (array $indexes, string $currentKey) use ($key) {
                    if (preg_match('(^' . preg_quote($key) . '\[(?<index>[^\]]+)\])', $currentKey, $matches)) {
                        $indexes[] = $matches['index'];
                    }

                    return $indexes;
                },
                []
            )
        );
    }

    public function isEmpty() : bool
    {
        return empty($this->data);
    }

    /**
     * @throws InvalidKey When an invalid key is encountered
     * @throws InvalidValue When an invalid value is encountered
     */
    private static function flattenNestedArray(array $nestedArray, string $prefix = '') : array
    {
        $flatArray = [];

        foreach ($nestedArray as $key => $value) {
            if (!is_string($key) && ('' === $prefix || !is_int($key))) {
                throw InvalidKey::fromNonNestedKey($key);
            }

            if ('' !== $prefix) {
                $key = $prefix . '[' . $key . ']';
            }

            if (is_string($value)) {
                $flatArray[$key] = $value;
                continue;
            }

            if (is_array($value)) {
                $flatArray += self::flattenNestedArray($value, $key);
                continue;
            }

            throw InvalidValue::fromNonNestedValue($value);
        }

        return $flatArray;
    }
}
