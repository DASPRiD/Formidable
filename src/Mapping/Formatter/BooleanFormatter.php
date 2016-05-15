<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;

final class BooleanFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : bool
    {
        switch ($data->getValue($key, 'false')) {
            case 'true':
                return true;

            case 'false':
                return false;
        }

        // @todo return invalid type error
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!is_bool($value)) {
            // @todo throw exception
        }

        return new Data([$key => $value ? 'true' : 'false']);
    }
}
