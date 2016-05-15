<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

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

    public function bind(Data $data)
    {
        $reflectionClass = new ReflectionClass($this->className);
        $reflectionMethod = $reflectionClass->getMethod('__construct');

        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $arguments[$reflectionParameter->getName()] = null;
        }

        foreach ($this->mappings as $key => $mapping) {
            $arguments[$key] = $mapping->bind($data);
        }

        return $reflectionClass->newInstance($arguments);
    }

    public function unbind($value) : Data
    {
        if (!$value instanceof $this->className) {
            // @todo throw exception
        }

        $data = new Data([]);

        foreach ($this->mappings as $mapping) {
            $data = $data->merge($mapping->unbind($value));
        }

        return $data;
    }

    public function withPrefix(string $prefix) : self
    {
        $objectMapping = clone $this;
        $objectMapping->key = $prefix . $objectMapping->key;

        return $objectMapping;
    }
}
