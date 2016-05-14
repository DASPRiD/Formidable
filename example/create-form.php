<?php
declare(strict_types = 1);

use DASPRiD\SimpleForm\Form;
use DASPRiD\SimpleForm\Mapping\FieldMapping;
use DASPRiD\SimpleForm\Mapping\ObjectMapping;

$form = new Form(new ObjectMapping([
    'name' => FieldMapping::text(),
    'address' => new ObjectMapping([
        'city' => FieldMapping::text(),
        'country' => FieldMapping::text(),
    ], AddressFormData::class),
    'groupIds' => new FieldSequenceMapping(FieldMapping::integer()),
], UserFormData::class));
