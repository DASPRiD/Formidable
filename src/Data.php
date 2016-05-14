<?php
declare(strict_types = 1);

namespace DASPRiD\SimpleForm;

final class Data
{
    /**
     * @var string[]
     */
    private $values;

    private function __construct()
    {
    }

    public static function fromArray(array $rawValues)
    {
        $data = new self();
        $data->values = self::flattenRawValues('', $rawValues);

        return $data;
    }

    public function get(string $key) : string
    {
        return $this->values[$key];
    }

    private static function flattenRawValues($prefix, array $rawValues, $useArraySuffix = false)
    {
        $values = [];

        foreach ($rawValues as $key => $rawValue) {
            if ($useArraySuffix) {
                $key .= '[' . $key . ']';
            }

            if (is_array($rawValue)) {
                $values += self::flattenRawValues($prefix . $key, $rawValues, true);
                continue;
            }

            if (is_string($rawValue)) {
                $values[$prefix . $key] = $rawValue;
                continue;
            }

            // @todo throw exception
        }

        return $values;
    }
}
