<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Data;

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
            $bindResult = $this->wrappedMapping->bind($data);

            if ($bindResult->isSuccess()) {
                return $this->applyConstraints($bindResult->getValue(), $this->key);
            }

            return $bindResult;
        }

        return $this->applyConstraints(null, $this->key);
    }

    public function unbind($value) : Data
    {
        if (null === $value) {
            return Data::fromFlatArray([]);
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
