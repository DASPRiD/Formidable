<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;

interface FormatterInterface
{
    /**
     * @return mixed
     */
    public function bind(Data $data);

    public function unbind(string $key, $value) : Data;
}
