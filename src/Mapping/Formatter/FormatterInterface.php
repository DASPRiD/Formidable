<?php
declare(strict_types = 1);

namespace DASPRiD\SimpleForm\Mapping\Formatter;

interface FormatterInterface
{
    /**
     * @return mixed
     */
    public function bind(array $data);

    public function unbind(string $key, $value) : array;
}
