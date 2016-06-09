All build-in mappings support additionally validation. To assign a constraint to a mapping, you can call the
`verifying(ConstraintInterface $constraint)` method on it, which will return a new instance of the mapping with the
constraint added to it. You can call the method multiple times to generated an object with multiple constraints
assigned.

While Formidable ships with a small set of constraints, those are primarily consumed by the `FieldMappingFactory`, so
generally you will want to write your own constraints. To do so, create a new class which implements the
`ConstaintInterface`. That class will have a single method `__invoke($value)', which must return a `ValidationResult`.
In case an empty validation result is returned, it is considered successful.

A constraint always gets the converted value passed. So in case of a field mapping, you'll get a string, float, integer
or similar. In case of an object mapping, you'll get an object and a repeated mapping would give you an array of the
wrapped mappings.

# Creating a simple constraint

Let's say you want to verify that an input matches a concrete pattern, your constraint could look like this:

```php
<?php
use Assert\Assertion;
use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;

class PatternConstraint implements ConstraintInterface
{
    public function __invoke($value) : ValidationResult
    {
        Assertion::string($value);

        if (!preg_match('(^[a-z]{5}$)', $value)) {
            return new ValidationResult(new ValidationError('error.pattern'));
        }

        return new ValidationResult();
    }
}
```

To assign the new constraint to a mapping, you could do something like this:

```php
<?php
$mapping = FieldMappingFactory::text()->verifying(new PatternConstraint());
```

!!!note "Type Assertion"
    You may ask yourself, what the assertion is for. Theoretically, you should always receive a string here, as long
    as you assign the constraint to the correct mapping. But since we do not have generics yet, you should actually
    assert the input type which you receive.

    You can install the assertion library via composer:

    ```
    $ composer require beberlei/assert
    ```

# Context validation

Sometimes you need to validate fields based on other fields in the form. Instead of assigning a constraint to the
specific field, where you wouldn't know the context, you actually assign it to the parent object. For example, you may
want to validate that two passwords are equal:

```php
<?php
use Assert\Assertion;
use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;

class PasswordConfirmationConstraint implements ConstraintInterface
{
    public function __invoke($value) : ValidationResult
    {
        Assertion::instanceOf($value, UserFormData::class);

        if ($value->getPassword() !== $value->getPasswordConfirm()) {
            return new ValidationResult(new ValidationError('error.password-mismatch', [], 'passwordConfirm'));
        }

        return new ValidationResult();
    }
}
```

Now when creating your mapping, you would assign the constraint to the object mapping:

```php
<?php
use DASPRiD\Formidable\Mapping\FieldMappingFactory;
use DASPRiD\Formidable\Mapping\ObjectMapping;

$mapping = (new ObjectMapping([
    'password' => FieldMappingFactory::text(1),
    'passwordConfirm' => FieldMappingFactory::text(),
], UserFormData::class))->verifying(new PasswordConfirmationConstraint());
```

As you probably noted, we passed a third parameter to the `ValidationError` in our constraint. This specifies to which
child mapping the validation error should be assigned. If we had omitted that parameter, the error would have been
assigned to the object mapping, which in case of a root mapping would have resulted in a global form error.
