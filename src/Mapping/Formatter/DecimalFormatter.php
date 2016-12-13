<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\Mapping\BindResult;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidTypeException;

final class DecimalFormatter implements FormatterInterface
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

        if (!is_numeric($value)) {
            return BindResult::fromFormErrors(new FormError(
                $key,
                'error.float'
            ));
        }

        return BindResult::fromValue($data->getValue($key));
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!is_string($value)) {
            throw InvalidTypeException::fromInvalidType($value, 'string');
        } elseif (!is_numeric($value)) {
            throw InvalidTypeException::fromNonNumericString($value);
        }

        return Data::fromFlatArray([$key => $value]);
    }
}
