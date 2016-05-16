<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Data;
use ReflectionClass;

final class ObjectMapping implements MappingInterface
{
    use MappingTrait;

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
    private $key;

    /**
     * @param MappingInterface[] $mappings
     */
    public function __construct(array $mappings, $className, string $key = '')
    {
        foreach ($mappings as $mappingKey => $mapping) {
            if (!is_string($key)) {
                // @todo exception
            }

            $this->mappings[$mappingKey] = $mapping->withPrefixAndRelativeKey($key, $mappingKey);
        }

        if (!class_exists($className)) {
            // @todo exception
        }

        $this->className = $className;
        $this->key = $key;
    }

    public function bind(Data $data) : BindResult
    {
        $reflectionClass = new ReflectionClass($this->className);
        $reflectionMethod = $reflectionClass->getMethod('__construct');

        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $arguments[$reflectionParameter->getName()] = null;
        }

        $formErrors = new \DASPRiD\Formidable\FormError\FormErrorSequence([]);

        foreach ($this->mappings as $key => $mapping) {
            $bindResult = $mapping->bind($data);

            if (!$bindResult->isSuccess()) {
                $formErrors = $formErrors->merge($bindResult->getFormErrors());
                continue;
            }

            if (!array_key_exists($key, $arguments)) {
                // @todo throw exception
            }

            $arguments[$key] = $mapping->bind($data);
        }

        if (0 < count($formErrors)) {
            return BindResult::fromFormErrors($formErrors);
        }

        return BindResult::fromValue($reflectionClass->newInstance($arguments));
    }

    public function unbind($value) : Data
    {
        if (!$value instanceof $this->className) {
            // @todo throw exception
        }

        $data = Data::fromFlatArray([]);

        foreach ($this->mappings as $mapping) {
            $data = $data->merge($mapping->unbind($value));
        }

        return $data;
    }

    public function withPrefixAndRelativeKey(string $prefix, string $relativeKey) : MappingInterface
    {
        return new self(
            $this->mappings,
            $this->className,
            $this->createKeyFromPrefixAndRelativeKey($prefix, $relativeKey)
        );
    }
}
