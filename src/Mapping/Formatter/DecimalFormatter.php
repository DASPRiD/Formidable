<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use Assert\Assertion;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\Mapping\BindResult;

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
        Assertion::string($value);
        Assertion::numeric($value);
        return Data::fromFlatArray([$key => $value]);
    }
}
