<?php
declare(strict_types = 1);

namespace DASPRiD\SimpleForm\Mapping\Formatter;

final class IntegerFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, array $data) : int
    {
        if (!array_key_exists($key, $data)) {
            // @todo return form error
        }

        if (!is_string($data[$key])) {
            // @todo return invalid type error
        }

        return (int) $data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : array
    {
        if (!is_integer($value)) {
            // @todo throw exception
        }

        return [$key => (string) $value];
    }
}
