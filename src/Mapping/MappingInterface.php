<?php
declare(strict_types = 1);

namespace DASPRiD\SimpleForm\Mapping;

interface MappingInterface
{
    /**
     * @return mixed
     */
    public function bind(array $data);

    public function unbind($value) : array;

    public function withPrefix(string $prefix) : self;
}
