<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper;

use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DOMDocument;

final class ErrorList
{
    use AttributeTrait;

    /**
     * @var ErrorFormatter
     */
    private $errorFormatter;

    public function __construct(ErrorFormatter $errorFormatter)
    {
        $this->errorFormatter = $errorFormatter;
    }

    public function __invoke(FormErrorSequence $errors, array $htmlAttributes = []) : string
    {
        if ($errors->isEmpty()) {
            return '';
        }

        $errorFormatter = $this->errorFormatter;
        $document = new DOMDocument('1.0', 'utf-8');
        $list = $document->createElement('ul');
        $document->appendChild($list);
        $this->addAttributes($list, $htmlAttributes);

        foreach ($errors as $error) {
            /* @var $error FormError */
            $list->appendChild($document->createElement(
                'li',
                htmlspecialchars($errorFormatter($error->getMessage(), $error->getArguments()))
            ));
        }

        return $document->saveHTML($list);
    }
}
