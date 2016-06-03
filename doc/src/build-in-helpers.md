Formidable ships with a few helpers, located in the `DASPRiD\Formidable\Helper` namespace:

# Input Helpers

There exist multiple input helpers to render out different inputs. They all have in common that they take a `Field`
object as the first and optionally an array of HTML attributes as the last argument. They will automatically set the
`name` attribute to the field's key and the `id` attribute to the field's key prepended by the string `input.`.

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

Renders a `<select>` element. Contrary to the other input helpers, the `Select` helper takes an array of options as
second argument. Each array element can either be a `string => string` element, which will render it out as a normal
`<option>`, with the array key being the value and the array value being the label, or it can be a `string => array`
element, which will render out an `<optgroup>', with the array key being the label and the array value being the child
options. Option groups can also be nested.

# Error Helpers

## `ErrorFormatter`

The error formatter helps to format messages and their arguments coming from validators. It includes messages in the
US english language for the provided validators, but also accepts custom messages through it's constructor, which must
be format strings compatible with the PHP's [MessageFormatter](http://php.net/manual/en/class.messageformatter.php).

## `ErrorList`

This helper uses the [`ErrorFormatter`](#errorformatter) helper to render out lists of errors. When invoked, it expects
a `DASPRiD\Formidable\FormError\FormErrorSequence` object, which is usually provided by `Form::getGlobalErrors()` or
`Field::getErrors()`.
