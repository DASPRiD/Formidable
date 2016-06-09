# Rendering forms manually

The idea behind Formidable is to give you as much control over the rendering process as possible. The simplest way to
approach this is by rendering the form manually, with data retrieved from the form object. The first thing to note is
that the form itself does not know which fields exists and which don't. This means that there is no multi-purpose code
which renders out the entire HTML based on a given form object. Instead, you have to actually know, which fields exist
and render each individually.

Based on the example form created in the [Getting Started](getting-started.md) section, it could easily be rendered out
like this:

```html
<form method="post">
    <?php if ($form->hasGlobalErrors()): ?>
        <ul class="errors">
            <?php foreach ($form->getGlobalErrors() as $error): ?>
                <li><?php echo $error->getMessage(); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

    <?php $field = $form->getField('name'); ?>
    <label for="input.name">Name</label>
    <input
        type="text"
        id="input.name"
        name="name"
        value="<?php echo htmlspecialchars($field->getValue()); ?>"
    >
    <?php if ($field->hasErrors()): ?>
        <ul class="errors">
            <?php foreach ($field->getErrors() as $error): ?>
                <li><?php echo $error->getMessage(); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

    <?php $field = $form->getField('emailAddress'); ?>
    <label for="input.emailAddress">Email Address</label>
    <input
        type="email"
        id="input.emailAddress"
        name="emailAddress"
        value="<?php echo htmlspecialchars($field->getValue()); ?>"
    >
    <?php if ($field->hasErrors()): ?>
        <ul class="errors">
            <?php foreach ($field->getErrors() as $error): ?>
                <li><?php echo $error->getMessage(); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

    <input type="submit">
</form>
```

This example builds everything manually to illustrate how rendering works at the basic level. You probably won't really
be writing the code for your forms like this, because it is quite repetitive. You will almost always want to take
advantage of helpers to render the HTML exactly the way the HTML/CSS design on a particular project expects it.

# Using Formidable helpers

Formidable ships a few general-purpose helpers which can be used as to render the most common form HTML. To render the
above form with said helpers, the code would look something like this:

```html
<?php
$errorList = new DASPRiD\Formidable\Helper\ErrorList();
$inputText = new DASPRiD\Formidable\Helper\InputText();
?>
<form method="post">
    <?php echo $errorList($form->getGlobalErrors(), ['class' => 'errors']); ?>

    <?php $field = $form->getField('name'); ?>
    <label for="input.name">Name</label>
    <?php echo $inputText($field); ?>
    <?php echo $errorList($field->getErrors(), ['class' => 'errors']); ?>

    <?php $field = $form->getField('emailAddress'); ?>
    <label for="input.emailAddress">Email Address</label>
    <?php echo $inputText($field, ['type' => 'email']); ?>
    <?php echo $errorList($field->getErrors(), ['class' => 'errors']); ?>

    <input type="submit">
</form>
```

As you can see, the code is a lot more compact. You can of course write your own helpers instead to minimize
the code even more, either by implementing the logic yourself or by re-using the Formidable helpers within your
own. Formidable ships with more helpers, suitable to render multiple kinds of inputs, which are described in the
[Built-in Helpers](built-in-helpers.md) section.

# Third-party helper packages

It is an intentional design decision that Formidable is not opinionated about which templating language and front-end
framework should or will be used.

It's possible that third-party helper packages could be created and shared, with particular design biases made with
regard to the templating language and the front-end framework. For example, one could imagine a Plates plus Bootstrap
forms helper package being built on top of Formidable.