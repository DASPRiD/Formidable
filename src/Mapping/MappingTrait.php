<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

trait MappingTrait
{
    protected function createKeyFromPrefixAndRelativeKey(string $prefix, string $relativeKey) : string
    {
        if ('' === $prefix) {
            return $relativeKey;
        }

        return $prefix . '[' . $relativeKey . ']';
    }
}
