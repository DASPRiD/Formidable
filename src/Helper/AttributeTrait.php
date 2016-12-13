<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper;

use DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeKeyException;
use DASPRiD\Formidable\Helper\Exception\InvalidHtmlAttributeValueException;
use DOMNode;

trait AttributeTrait
{
    protected function addAttributes(DOMNode $node, array $htmlAttributes)
    {
        foreach ($htmlAttributes as $key => $value) {
            if (!is_string($key)) {
                throw InvalidHtmlAttributeKeyException::fromInvalidKey($key);
            }

            if (!is_string($value)) {
                throw InvalidHtmlAttributeValueException::fromInvalidValue($value);
            }

            $node->setAttribute($key, $value);
        }
    }
}
