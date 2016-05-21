<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Transformer;

interface TransformerInterface
{
    public function __invoke(string $value, string $key) : string;
}
