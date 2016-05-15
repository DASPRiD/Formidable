<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;

final class TextFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : string
    {
        if (!$data->hasValue($key)) {
            // @todo check if required
        }

        return $data->getValue($key);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!is_string($value)) {
            // @todo throw exception
        }

        return new Data([$key => $value]);
    }
}
