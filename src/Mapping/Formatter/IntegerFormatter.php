<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Mapping\BindResult;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidValue;

final class IntegerFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : BindResult
    {
        if (!$data->hasKey($key)) {
            return BindResult::fromFormErrors(new FormErrorSequence(new FormError(
                $key,
                'error.required'
            )));
        }

        $value = $data->getValue($key);

        if (!preg_match('(^[1-9]\d*$)', $value)) {
            return BindResult::fromFormErrors(new FormErrorSequence(new FormError(
                $key,
                'error.integer'
            )));
        }

        return BindResult::fromValue((int) $data[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!is_integer($value)) {
            throw InvalidValue::fromNonInteger($value);
        }

        return new Data([$key => (string) $value]);
    }
}
