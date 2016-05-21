<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use Assert\Assertion;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
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

        return BindResult::fromFormErrors(new FormError(
            $key,
            'error.boolean'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        Assertion::boolean($value);
        return Data::fromFlatArray([$key => $value ? 'true' : 'false']);
    }
}
