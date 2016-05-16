<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Mapping\BindResult;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidValue;

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
            'error.boolean'
        )));
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!is_bool($value)) {
            throw InvalidValue::fromNonBoolean($value);
        }

        return Data::fromFlatArray([$key => $value ? 'true' : 'false']);
    }
}
