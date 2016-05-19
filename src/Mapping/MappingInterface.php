<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;

interface MappingInterface
{
    public function bind(Data $data) : BindResult;

    public function unbind($value) : Data;

    public function withPrefixAndRelativeKey(string $prefix, string $relativeKey) : MappingInterface;

    public function verifying(ConstraintInterface ...$constraints);
}
