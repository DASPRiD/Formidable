<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

final class Data
{
    /**
     * @var array
     */
    private $data;

    private function __construct(array $data)
    {
        self::validateData($data);
        $this->data = $data;
    }

    public function merge(self $data) : self
    {
        $newData = clone $this;
        $newData->data = $newData->data + $data->data;

        return $newData;
    }

    public function getValue(string $key, string $fallback = null) : string
    {
        $value = $this->getNode($key);

        if (null === $value && null !== $value) {
            return $fallback;
        }

        if (null === $value) {
            // @todo thow exception
        } elseif (!is_string($value)) {
            // @todo throw exception
        }

        return $value;
    }

    public function getIndexes(string $key) : array
    {
        $node = $this->getNode($key);

        if (!is_array($node)) {
            return [];
        }

        return array_keys($key);
    }

    public function getValues(string $key) : array
    {
        $node = $this->getNode($key);

        if (!is_array($node)) {
            return [];
        }

        $values = [];

        foreach ($node as $key => $value) {
            if (!is_string($value)) {
                continue;
            }

            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * @return array|string|null
     */
    private function getNode(string $key)
    {
        if (!preg_match('(^(?<head>\w+)(?<chilren>(?:\[\w+\]))*$)', $key, $matches)) {
            // @todo throw exception
        }

        if (!array_key_exists($matches['head'], $this->data)) {
            return null;
        }

        $currentNode = $this->data[$matches['head']];

        if ('' === $matches['children']) {
            return $currentNode;
        }

        $nodes = explode('][', trim($matches['children'], '[]'));

        foreach ($nodes as $node) {
            if (!array_key_exists($node, $currentNode)) {
                return null;
            }

            $currentNode = $currentNode[$node];
        }

        return $currentNode;
    }

    private static function validateData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!is_string($key)) {
                // @todo throw exception
            }

            if (is_array($value)) {
                self::validateData($value);
                continue;
            }

            if (!is_string($value)) {
                // @todo throw exception
            }
        }
    }
}
