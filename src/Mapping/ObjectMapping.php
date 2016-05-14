<?php
declare(strict_types = 1);

namespace DASPRiD\SimpleForm\Mapping;

use ReflectionClass;

final class ObjectMapping implements MappingInterface
{
    /**
     * @var array
     */
    private $mappings;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $key = '';

    /**
     * @param MappingInterface[] $mappings
     * @param string $className
     */
    public function __construct(array $mappings, $className)
    {
        foreach ($mappings as $key => $mapping) {
            if (!is_string($key)) {
                // @todo exception
            }

            $this->mappings[$key] = $mapping->withPrefix($key);
        }

        if (!class_exists($className)) {
            // @todo exception
        }

        $this->className = $className;
    }

    public function bind(array $data)
    {
        $reflectionClass = new ReflectionClass($this->className);
        $reflectionMethod = $reflectionClass->getMethod('__construct');

        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $arguments[$reflectionParameter->getName()] = null;
        }

        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $arguments) || !array_key_exists($key, $this->mappings)) {
                continue;
            }

            $arguments[$key] = $this->mappings[$key]->bind($data);
        }

        return $reflectionClass->newInstance($arguments);
    }

    public function unbind($value) : array
    {
        if (!$value instanceof $this->className) {
            // @todo throw exception
        }

        $values = [];

        foreach ($this->mappings as $mapping) {
            $values += $mapping->unbind($value);
        }

        return $values;
    }

    public function withPrefix(string $prefix) : self
    {
        $objectMapping = clone $this;
        $objectMapping->key = $prefix . $objectMapping->key;

        return $objectMapping;
    }
}
