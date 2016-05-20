<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use Assert\Assertion;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormErrorSequence;

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

    public function bind(Data $data) : BindResult
    {
        $values = [];
        $formErrors = new FormErrorSequence();

        foreach ($data->getIndexes($this->key) as $index) {
            $bindResult = $this->wrappedMapping->withPrefixAndRelativeKey($this->key . '[' . $index . ']')->bind($data);

            if (!$bindResult->isSuccess()) {
                $formErrors = $formErrors->merge($bindResult->getFormErrors());
                continue;
            }

            $values[] = $bindResult->getValue();
        }

        if (!$formErrors->isEmpty()) {
            return BindResult::fromFormErrors($formErrors);
        }

        return $this->applyConstraints($values);
    }

    public function unbind($value) : Data
    {
        Assertion::isArray($value);
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
