<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Mapping\BindResult;

final class BooleanFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : BindResult
    {
        switch ($data->getValue($key, 'false')) {
            case 'true':
                return BindResult::fromValue(true);

            case 'false':
                return BindResult::fromValue(false);
        }

        return BindResult::fromFormErrors(new FormErrorSequence(new FormError(
            $key,
            'invalid.boolean'
        )));
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
