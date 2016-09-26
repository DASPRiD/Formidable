<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use Assert\Assertion;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use ReflectionClass;
use ReflectionProperty;

final class ObjectMapping implements MappingInterface
{
    use MappingTrait;

    /**
     * @var array
     */
    private $mappings = [];

    /**
     * @var string
     */
    private $className;

    /**
     * @var callable
     */
    private $apply;

    /**
     * @var callable
     */
    private $unapply;

    /**
     * @var string
     */
    private $key = '';

    /**
     * @param MappingInterface[] $mappings
     */
    public function __construct(array $mappings, string $className, callable $apply = null, callable $unapply = null)
    {
        foreach ($mappings as $mappingKey => $mapping) {
            Assertion::string($mappingKey);
            Assertion::isInstanceOf($mapping, MappingInterface::class);

            $this->mappings[$mappingKey] = $mapping->withPrefixAndRelativeKey($this->key, $mappingKey);
        }

        Assertion::classExists($className);

        if (null === $apply) {
            $apply = function (...$arguments) {
                return new $this->className(...array_values($arguments));
            };
        }

        if (null === $unapply) {
            $unapply = function ($value) {
                Assertion::isInstanceOf($value, $this->className);

                $values = [];
                $reflectionClass = new ReflectionClass($this->className);

                foreach ($reflectionClass->getProperties() as $property) {
                    /* @var $property ReflectionProperty */
                    $property->setAccessible(true);
                    $values[$property->getName()] = $property->getValue($value);
                }

                return $values;
            };
        }

        $this->className = $className;
        $this->apply = $apply;
        $this->unapply = $unapply;
    }

    public function withMapping(string $key, MappingInterface $mapping) : self
    {
        $clone = clone $this;
        $clone->mappings[$key] = $mapping->withPrefixAndRelativeKey($clone->key, $key);

        return $clone;
    }

    public function bind(Data $data) : BindResult
    {
        $arguments = [];
        $formErrorSequence = new FormErrorSequence();

        foreach ($this->mappings as $key => $mapping) {
            $bindResult = $mapping->bind($data);

            if (!$bindResult->isSuccess()) {
                $formErrorSequence = $formErrorSequence->merge($bindResult->getFormErrorSequence());
                continue;
            }

            $arguments[$key] = $bindResult->getValue();
        }

        if (!$formErrorSequence->isEmpty()) {
            return BindResult::fromFormErrorSequence($formErrorSequence);
        }

        $apply = $this->apply;
        $value = $apply(...array_values($arguments));

        Assertion::isInstanceOf($value, $this->className);

        return $this->applyConstraints($value, $this->key);
    }

    public function unbind($value) : Data
    {
        $data = Data::none();
        $unapply = $this->unapply;
        $values = $unapply($value);
        Assertion::isArray($values);

        foreach ($this->mappings as $key => $mapping) {
            Assertion::keyExists($values, $key);
            $data = $data->merge($mapping->unbind($values[$key]));
        }

        return $data;
    }

    public function withPrefixAndRelativeKey(string $prefix, string $relativeKey) : MappingInterface
    {
        $clone = clone $this;
        $clone->key = $this->createKeyFromPrefixAndRelativeKey($prefix, $relativeKey);
        $clone->mappings = [];

        foreach ($this->mappings as $mappingKey => $mapping) {
            $clone->mappings[$mappingKey] = $mapping->withPrefixAndRelativeKey($clone->key, $mappingKey);
        }

        return $clone;
    }
}
