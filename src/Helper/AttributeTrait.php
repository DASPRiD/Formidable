<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper;

use Assert\Assertion;
use DOMNode;

trait AttributeTrait
{
    protected function addAttributes(DOMNode $node, array $htmlAttributes)
    {
        foreach ($htmlAttributes as $key => $value) {
            Assertion::string($key);
            Assertion::string($value);

            $node->setAttribute($key, $value);
        }
    }
}
