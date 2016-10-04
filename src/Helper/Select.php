<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper;

use Assert\Assertion;
use DASPRiD\Formidable\Field;
use DOMDocument;
use DOMNode;

final class Select
{
    use AttributeTrait;

    public function __invoke(Field $field, array $options, array $htmlAttributes = []) : string
    {
        $htmlAttributes['id'] = 'input.' . $field->getKey();

        if (array_key_exists('multiple', $htmlAttributes)) {
            $htmlAttributes['name'] = $field->getKey() . '[]';
            $selectedValues = $field->getNestedValues();
        } else {
            $htmlAttributes['name'] = $field->getKey();
            $selectedValues = [$field->getValue()];
        }

        $document = new DOMDocument('1.0', 'utf-8');
        $select = $document->createElement('select');
        $document->appendChild($select);
        $this->addAttributes($select, $htmlAttributes);
        $this->addOptions($document, $select, $options, $selectedValues);

        return $document->saveHTML($select);
    }

    private function addOptions(DOMDocument $document, DOMNode $node, array $options, array $selectedValues)
    {
        foreach ($options as $value => $label) {
            if (is_int($value)) {
                $value = (string) $value;
            } else {
                Assertion::string($value);
            }

            Assertion::true(is_string($label) || is_array($label));

            if (is_array($label)) {
                $optgroup = $document->createElement('optgroup');
                $this->addAttributes($optgroup, ['label' => $value]);
                $this->addOptions($document, $optgroup, $label, $selectedValues);
                $node->appendChild($optgroup);
                continue;
            }

            $option = $document->createElement('option');
            $option->appendChild($document->createTextNode($label));
            $htmlAttributes = ['value' => $value];

            if (in_array($value, $selectedValues)) {
                $htmlAttributes['selected'] = 'selected';
            }

            $this->addAttributes($option, $htmlAttributes);
            $node->appendChild($option);
        }
    }
}
