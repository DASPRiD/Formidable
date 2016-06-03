# Creating your first form

In Formidable, forms are supposed to handle all your user input, validate it and give your strictly typed form data
back. To achieve this, each form gets a mapping assigned which exactly specify how to handle the given data. To create
your first form, let's take a very simple example:

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

This will create a very basic form with two fields, a name, which must be at least one character long, and an email
address. An object mapping always maps form data to an object, which is considered to be an intermediate transfer
object. The object mapping will inject the mapped data via constructor injection to create the form data object, and
read the properties via reflection for filling the form from form data. A matching form data object would looks like
this:

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

As we have not specified an `apply()` or `unapply()` callable when creating the object mapping, it is going to use the
default functions supplied with Formidable. This means that the constructor will receive the arguments in the order in
which they were specified during construction. When data are extracted from the form data for unbinding, all values will
be extracted via reflection from the object, so the property names must match the mapping names.

# Using the form to handle input

Now since you got your form created, let's use it to validate some input. Formidable is build with PSR-7 compatibility
in mind, so when you are using a framework based on PSR-7, it becomes really easy to inject your data:

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

The first thing which may come to your mind is, why the `$form` variable was re-assigned when binding the request. This
has the very simple reason that anything in Formidable is immutable. Thus, when you bind a request to a form or try to
make any other changes, it will actually clone itself and return the clone with the changes applied. This guarantees
that the original form object is always stateless and can be re-used in other places.

# Rendering the form

Rendering forms can be done completely manually, or by using helpers such as those provided by Formidable. The process
of doing so is out of scope of this guide. It is described in detail in the [Rendering Forms](rendering-forms.md)
section.