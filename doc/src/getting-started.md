# Creating your first form

In Formidable, forms handle all your POST array input[^file-uploads], validate it, and return typed form data. To
achieve this, each form gets a mapping assigned which specifies how to handle the required data types. Let's look at a
simple example:

```php
<?php
use DASPRiD\Formidable\Form;
use DASPRiD\Formidable\Mapping\FieldMappingFactory;
use DASPRiD\Formidable\Mapping\ObjectMapping;

$form = new Form(new ObjectMapping([
    'name' => FieldMappingFactory::text(1),
    'emailAddress' => FieldMappingFactory::emailAddress(),
], PersonFormData::class));
```

This will create a basic form with two fields:

- a name (which must be at least one character long)
- an email address

The ObjectMapping class defines how the form fields map to a typed form data object. The form data object is an
intermediate transfer object, distinct from an entity. It is the bridge between the form and the entity. It is
responsible for defining and enforcing data types and input validation rules. Once data has been mapped into the form
data object with no errors, it can be considered filtered, valid, and type safe, making it trivial to populate an entity
or database row.

The form data object accepts data via constructor injection, and reads the properties for populating the Form from the
data via reflection. The PersonFormData from the above example looks like this:

```
<?php
final class PersonFormData
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $emailAddress;

    public function __construct(string $name, string $emailAddress)
    {
        $this->name = $name;
        $this->emailAddress = $emailAddress;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmailAddress() : string
    {
        return $this->emailAddress;
    }
}
```

As we have not specified an `apply()` or `unapply()` callable when instantiating the `ObjectMapping`, it is going to use
the default functions supplied with Formidable. This means that the `PersonFormData` constructor will receive the
arguments in the order in which they were specified during object mapping construction. When data are extracted from the
form data for unbinding, all values will be extracted via reflection from the object, so the property names must match
the mapping names.

# Using the form to handle input

Now that the form is created, let's use it to validate some input. Formidable is build with PSR-7 compatibility in mind,
so when you are using a framework based on PSR-7, it becomes really easy to inject your data:

```php
<?php
$form = $form->bindFromRequest($psr7ServerRequest);

if (!$form->hasErrors()) {
    /* @var $personFormData PersonFormData */
    $personFormData = $form->getValue();

    // You may use $personFormData now to populate some entity or store the data in a database.
}

// At this point, the form validation found an error, so you should re-display the form.
```

!!!note "A note about immutability"

    You may have noticed that the `$form` variable was re-assigned when binding the request. This is because everything
    in Formidable is immutable. Thus, when you bind a request to a form or try to make any other changes, it will
    actually clone itself and return the clone with the changes applied. This guarantees that the original form instance
    is stateless and can be re-used in other places without ambiguous state.

# Rendering the form

Rendering forms can be done manually, or by using helpers such as those provided by Formidable. The process of doing so
is described in detail in the [Rendering Forms](rendering-forms.md) section.

[^file-uploads]: Formidable doesn't handle file uploads at this time, as we currently consider that out of scope.
