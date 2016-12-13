<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Mapping\Exception\BindFailureException;
use DASPRiD\Formidable\Mapping\Exception\InvalidMappingException;
use DASPRiD\Formidable\Mapping\Exception\InvalidMappingKeyException;
use DASPRiD\Formidable\Mapping\Exception\InvalidUnapplyResultException;
use DASPRiD\Formidable\Mapping\Exception\MappedClassMismatchException;
use DASPRiD\Formidable\Mapping\Exception\NonExistentMappedClassException;
use DASPRiD\Formidable\Mapping\Exception\NonExistentUnapplyKeyException;
use DASPRiD\Formidable\Mapping\Exception\UnbindFailureException;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

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
            if (!is_string($mappingKey)) {
                throw InvalidMappingKeyException::fromInvalidMappingKey($mappingKey);
            }

            if (!$mapping instanceof MappingInterface) {
                throw InvalidMappingException::fromInvalidMapping($mapping);
            }

            $this->mappings[$mappingKey] = $mapping->withPrefixAndRelativeKey($this->key, $mappingKey);
        }

        if (!class_exists($className)) {
            throw NonExistentMappedClassException::fromNonExistentClass($className);
        }

        if (null === $apply) {
            $apply = function (...$arguments) {
                return new $this->className(...array_values($arguments));
            };
        }

        if (null === $unapply) {
            $unapply = function ($value) {
                if (!$value instanceof $this->className) {
                    throw MappedClassMismatchException::fromMismatchedClass($this->className, $value);
                }

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
            try {
                $bindResult = $mapping->bind($data);
            } catch (Throwable $e) {
                throw BindFailureException::fromBindException($key, $e);
            }

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

        if (!$value instanceof $this->className) {
            throw MappedClassMismatchException::fromMismatchedClass($this->className, $value);
        }

        return $this->applyConstraints($value, $this->key);
    }

    public function unbind($value) : Data
    {
        $data = Data::none();
        $unapply = $this->unapply;
        $values = $unapply($value);

        if (!is_array($values)) {
            throw InvalidUnapplyResultException::fromInvalidUnapplyResult($values);
        }

        foreach ($this->mappings as $key => $mapping) {
            if (!array_key_exists($key, $values)) {
                throw NonExistentUnapplyKeyException::fromNonExistentUnapplyKey($key);
            }

            try {
                $data = $data->merge($mapping->unbind($values[$key]));
            } catch (Throwable $e) {
                throw UnbindFailureException::fromUnbindException($key, $e);
            }
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
