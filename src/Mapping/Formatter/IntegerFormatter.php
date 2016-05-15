<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;

final class IntegerFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : int
    {
        if (!$data->hasValue($key)) {
            // @todo check if required
        }

        $value = $data->getValue($key);

        if (!preg_match('(^[1-9]\d*$)', $value)) {
            // @todo validation error
        }

        return (int) $data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!is_integer($value)) {
            // @todo throw exception
        }

        return new Data([$key => (string) $value]);
    }
}
