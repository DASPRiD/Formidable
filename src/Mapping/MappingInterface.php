<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Data;

interface MappingInterface
{
    /**
     * @return mixed
     */
    public function bind(Data $data);

    public function unbind($value) : Data;

    public function withPrefix(string $prefix) : self;
}
