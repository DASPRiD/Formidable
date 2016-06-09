Mappings are a fundamental part of Formidable. They are responsible for converting the input data into PHP value types,
as well as validating them and converting PHP value types back to strings to be used for rendering the form. Formidable
comes with a set of mappings to cover all generic cases. These are located in the `DASPRiD\Formidable\Mapping`
namespace:

# `ObjectMapping` class

This mapping handles conversion between PHP objects and sets of values. During construction, it takes two arguments:

* An array of mappings, where each key corresponds to a POST parameter
* The class name of a form data object, to which the values are mapped

By default, all values are passed to the form data constructor in the order in which they were specified in the object
mapping array, and read via reflection from the form data object. This means that your form data constructor *requires
the values in exactly the same order* and that the *properties must be named the same as the array keys*.

Optionally, you can pass an `apply()` and `unapply()` callable, which take care of binding and unbinding
the object.

## Optional `apply()` and `unapply()` callables

* The `apply()` callable will also *require all parameters in the order in which they were specified* during object
mapping construction and must return a class of the type specified in the object mapping.

* The `unapply()` method will get the object passed in as single parameter and must return an array with all values,
having the same key as within the object mapping.

## Nesting `MappingInterface` values

You can pass any kind of mapping to the object mapping, as long as it implements the `MappingInterface`. You can even
have another object mapping as a child. This allows you to have more complex value objects. To explain how the fields
are named within HTML, take this example:

```php
use DASPRiD\Formidable\Mapping\FieldMappingFactory;
use DASPRiD\Formidable\Mapping\ObjectMapping;

$mapping = new ObjectMapping([
    'child' => new ObjectMapping([
        'text' => FieldMappingFactory::text(),
    ]),
], SomeFormData::class);
```

Your HTML field would have to look like this:

```html
<input type="text" name="child[text]" value="<?php echo $form->getField('child[text]')->getValue(); ?>">
```

# `OptionalMapping` class

A simple mapping which wraps around another mapping. It checks whether the value exists in the input data and that it is
not an empty string. If that is true, it will bind the `null` value, otherwise it will invoke the wrapped mapping.

# `RepeatedMapping` class

This mapping helps to repeat a given mapping multiple times. It can wrap around any other kind of mapping, so you can
have both repeated single fields as well as repeated fieldsets by wrapping around an `ObjectMapping`. When binding,
the mapping will return an array of bind values taken from the wrapped mapping.

Consider a simple example where you have a multi select. You'd wrap a `RepeatedMapping` around a `FieldMapping` for
that:

```php
use DASPRiD\Formidable\Mapping\FieldMappingFactory;
use DASPRiD\Formidable\Mapping\ObjectMapping;
use DASPRiD\Formidable\Mapping\RepeatedMapping;

$mapping = new ObjectMapping([
    'selections' => new RepeatedMapping(FieldMappingFactory::text()),
], SomeFormData::class);
```

In this case, your select object would have to look like this:

```html
<select name="selections[]" multiple></select>
```

# `FieldMapping` class

This mapping type is fundamental in converting string data to typed PHP values. By itself, the field mapping does not
know, how to bind and unbind the actual value it receives. For that purpose, a formatter is passed to the the field
mapping during construction. Formidable comes with a set of standard formatters and a factory for easily creating
field mappings with them. The `FieldMappingFactory` has the following methods which will all return configured field
mappings:

* `text($minLength = 0, $maxLength = null, $encoding = 'utf-8')`
  * Creates a simple string-to-string mapping, optionally applying constraints for the length of the string.

* `emailAddress()`
  * Creates a mapping which requires the input string to be a valid email address.

* `integer()`
  * Creates a mapping between input strings and PHP integers, validating that the input string is actually a valid
    integer string.

* `float()`
  * Creates a mapping between input strings and PHP floats, validating that the input string is actually a valid float
    string.

* `decimal()`
  * Similar to `float()`, but keeps the the number as a string after validation.

* `boolean()`
  * Creates a mapping between input strings and PHP booleans. The input string must either be `"true"`, `"false"` or be
    absent. This latter special requirement is because unchecked checkboxes are not transferred at all.

* `time(DateTimeZone $timeZone = null)`
  * Creates a mapping for `<input type="time">` to `ImmutableDateTime`. By default, times are treated as UTC, but you
    can also pass a custom time zone to the factory.

* `date(DateTimeZone $timeZone = null)`
  * Creates a mapping for `<input type="date">` to `ImmutableDateTime`. By default, times are treated as UTC, but you
    can also pass a custom time zone to the factory.

* `dateTime(DateTimeZone $timeZone = null, $localTime = false)`
  * Creates a mapping for `<input type="datetime">` and `<input type="datetime-local">` to `ImmutableDateTime`. By
    default, times are treated as UTC, but you can also pass a custom time zone to the factory. When using type
    `datetime`, you don't have to set the `$localTime` parameter. When using the `datetime-local` type, the browser will
    not submit a time zone, so it is important to pass a time zone to the factory in which the datetime should be
    interpreted, if not UTC, and set `$localTime` to `true`.
