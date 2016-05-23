<?php
declare(strict_types = 1);

use DASPRiD\Formidable\Mapping\FieldMappingFactory;
use DASPRiD\Formidable\Mapping\OptionalMapping;
use DASPRiD\Formidable\Mapping\RepeatedMapping;
use DASPRiD\SimpleForm\Form;
use DASPRiD\SimpleForm\Mapping\FieldMapping;
use DASPRiD\SimpleForm\Mapping\ObjectMapping;

$form = new Form((new ObjectMapping([
    'password' => FieldMappingFactory::text(1),
    'passwordConfirm' => FieldMappingFactory::text(1),
    'name' => new OptionalMapping(FieldMappingFactory::emailAddress()),
    'address' => new ObjectMapping([
        'city' => FieldMapping::text(),
        'country' => FieldMapping::text(),
    ], AddressFormData::class),
    'groupIds' => new RepeatedMapping(FieldMappingFactory::integer()->verifying(new GroupIdConstraint())),
], UserFormData::class))->verifying(new PasswordConfirmConstraint()));

//$form = $form->bindFromRequest($psr7Request, false);

$data = DASPRiD\Formidable\Data::fromNestedArray($psr7Request->getParsedBody());
$form->bind($data->transform($transformer));

if (!$form->hasErrors()) {
    // persistence
}

$form->fill(UserFormData::fromUser($user));

/**
address[name][firstName]
address[name][lastName]
address[city]
address[country]
address[contactMethods][0][type] = 'email'
address[contactMethods][0][value] = 'foo@bar.com'
address[contactMethods][1][type] = 'email'
address[contactMethods][1][value] = 'foo@bar.com'
address[groupIds][0]
groupIds[0]
groupIds[1]
*/

?>

<form method="POST">
    <?php $contactMethods = $form->getField('address[contactMethods]'); ?>
    <?php foreach ($contactMethods->getIndexes() as $index): ?>
        <fieldset>
            <?php $field = $form->getField('address[contactMethods[' . $index . '][type]'); ?>
            <input type="text" value="<?php $field->getValue(); ?>" name="<?php echo $field->getKey(); ?>">
            <?php $field->getErrors(); ?>
            <?php $field = $form->getField('address[contactMethods[' . $index . '][value]'); ?>
            <label<?php if (!$field->getErrors()->isEmpty()): ?> class="error"<?php endif ;?>>Value</label>
            <input type="text" value="<?php $field->getValue(); ?>" name="<?php echo $field->getKey(); ?>">
        </fieldset>
    <?php endforeach ?>

    <?php $field = $form->getField('groupIds'); ?>
    <select name="">
        <?php foreach ($form->getField('groupIds')->getIndexes() as $index): ?>
        <?php endforeach; ?>
    </select>
</form>
