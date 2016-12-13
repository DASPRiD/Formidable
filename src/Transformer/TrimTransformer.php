<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Transformer;

final class TrimTransformer implements TransformerInterface
{
    public function __invoke(string $value, string $key) : string
    {
        return trim($value);
    }
}
