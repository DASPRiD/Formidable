<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper;

use DASPRiD\Formidable\Field;
use DOMDocument;

final class Textarea
{
    use AttributeTrait;

    public function __invoke(Field $field, array $htmlAttributes = []) : string
    {
        $htmlAttributes['id'] = 'input.' . $field->getKey();
        $htmlAttributes['name'] = $field->getKey();

        $document = new DOMDocument('1.0', 'utf-8');
        $textarea = $document->createElement('textarea');
        $textarea->appendChild($document->createTextNode($field->getValue()));
        $document->appendChild($textarea);
        $this->addAttributes($textarea, $htmlAttributes);

        return $document->saveHTML($textarea);
    }
}
