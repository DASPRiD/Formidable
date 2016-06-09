Formidable ships with a few helpers, located in the `DASPRiD\Formidable\Helper` namespace.

The guiding principles here are as follows:

* Support the [HTML Form Input Types](http://www.w3schools.com/html/html_form_input_types.asp)
* Limit the markup output to the tags required for the input type

# Input Helpers

Formidable input helpers all take a `Field` object as the first argument, and optionally an array of HTML attributes as
the last argument. They will automatically set the `name` attribute to the field's key and the `id` attribute to the
field's key prepended by the string `input.`.

## `InputText`

Renders a simple text input, with the type defaulting to "text". You can override the type by supplying a different type
like "date", "color" or similar as HTML attribute.

## `InputPassword`

Works exactly like [`InputText`](#inputtext), but will not render a `value` attribute.

## `Textarea`

Renders a `<textarea>` element.

## `InputCheckbox`

Renders out a single checkbox and marks it as checked if the the field value equals "true".

## `Select`

Renders a `<select>` element. The `Select` helper takes an extra second argument that the other helpers don't need: an
array of options. Each options element can either be a `string => string` or it can be a `string => array`.

* `string => string`
  * A normal `<option>`, with the array key being the value and the array value being the label
* `string => array`
  * An `<optgroup>`, with the array key being the label and the array value being the child options
  * Option groups can also be nested.

# Error Helpers

## `ErrorFormatter`

The error formatter helps to format messages and their arguments coming from validators. It includes messages in the US
english language for the provided validators, but also accepts custom messages through it's constructor, which must be
format strings compatible with the PHP's [MessageFormatter](http://php.net/manual/en/class.messageformatter.php).

## `ErrorList`

This helper uses the [`ErrorFormatter`](#errorformatter) helper to render out lists of errors. When invoked, it expects
a `DASPRiD\Formidable\FormError\FormErrorSequence` object, which is usually provided by `Form::getGlobalErrors()` or
`Field::getErrors()`.
