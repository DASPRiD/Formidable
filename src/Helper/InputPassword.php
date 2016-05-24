<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper;

use DASPRiD\Formidable\Field;
use DOMDocument;

final class InputPassword
{
    use AttributeTrait;

    public function __invoke(Field $field, array $htmlAttributes = []) : string
    {
        $htmlAttributes['type'] = 'password';
        $htmlAttributes['id'] = 'input.' . $field->getKey();
        $htmlAttributes['name'] = $field->getKey();

        $document = new DOMDocument('1.0', 'utf-8');
        $input = $document->createElement('input');
        $document->appendChild($input);
        $this->addAttributes($input, $htmlAttributes);

        return $document->saveHTML($input);
    }
}
