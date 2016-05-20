<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use Assert\Assertion;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormErrorSequence;

final class OptionalMapping implements MappingInterface
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
        if (!$data->filter(function (string $value, string $key) {
            if ($key !== $this->key && 0 !== strpos($key, $this->key . '[')) {
                return false;
            }

            if ('' === $value) {
                return false;
            }

            return true;
        })->isEmpty()) {
            return $this->wrappedMapping->bind($data);
        }

        return BindResult::fromValue(null);
    }

    public function unbind($value) : Data
    {
        if (null === $value) {
            return new Data([]);
        }

        return $this->wrappedMapping->unbind($value);
    }

    public function withPrefixAndRelativeKey(string $prefix, string $relativeKey) : MappingInterface
    {
        $clone = clone $this;
        $clone->key = $this->createKeyFromPrefixAndRelativeKey($prefix, $relativeKey);
        $clone->wrappedMapping = $clone->wrappedMapping->withPrefixAndRelativeKey($prefix, $relativeKey);
        return $clone;
    }
}
