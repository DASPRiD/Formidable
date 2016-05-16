<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\BindResult;

interface FormatterInterface
{
    public function bind(string $key, Data $data) : BindResult;

    public function unbind(string $key, $value) : Data;
}
