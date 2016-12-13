<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\Mapping\BindResult;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidTypeException;

final class IntegerFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : BindResult
    {
        if (!$data->hasKey($key)) {
            return BindResult::fromFormErrors(new FormError(
                $key,
                'error.required'
            ));
        }

        $value = $data->getValue($key);

        if (!preg_match('(^-?[1-9]*\d+$)', $value)) {
            return BindResult::fromFormErrors(new FormError(
                $key,
                'error.integer'
            ));
        }

        return BindResult::fromValue((int) $data->getValue($key));
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!is_int($value)) {
            throw InvalidTypeException::fromInvalidType($value, 'int');
        }

        return Data::fromFlatArray([$key => (string) $value]);
    }
}
