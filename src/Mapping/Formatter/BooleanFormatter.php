<?php
declare(strict_types = 1);

namespace DASPRiD\SimpleForm\Mapping\Formatter;

final class BooleanFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function bind(string $key, array $data) : bool
    {
        $value = 'false';

        if (array_key_exists($key, $data)) {
            $value = $data[$key];
        }

        switch ($value) {
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
    public function unbind(string $key, $value) : array
    {
        if (!is_bool($value)) {
            // @todo throw exception
        }

        return [$key => $value ? 'true' : 'false'];
    }
}
