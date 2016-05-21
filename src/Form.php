<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

use Assert\Assertion;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Mapping\MappingInterface;
use DASPRiD\Formidable\Transformer\TrimTransformer;
use Psr\Http\Message\ServerRequestInterface;

final class Form
{
    /**
     * @var MappingInterface
     */
    private $mapping;

    /**
     * @var Data
     */
    private $data = [];

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var FormErrorSequence
     */
    private $errors;

    public function __construct(MappingInterface $mapping)
    {
        $this->mapping = $mapping;
    }

    public function fill($formData) : self
    {
        $form = clone $this;
        $form->data = $this->mapping->unbind($formData);
        $form->value = $formData;
        return $form;
    }

    public function bind(Data $data) : self
    {
        $form = clone $this;
        $form->data = $data;

        $bindResult = $this->mapping->bind($data);

        if ($bindResult->isSuccess()) {
            $form->value = $bindResult->getValue();
        } else {
            $form->errors = $bindResult->getFormErrors();
        }

        return $form;
    }

    public function bindFromRequest(ServerRequestInterface $request, bool $trimData = true) : self
    {
        if ('POST' === $request->getMethod()) {
            $data = Data::fromNestedArray($request->getParsedBody());
        } else {
            $data = Data::fromNestedArray($request->getQueryParams());
        }

        if ($trimData) {
            $data = $data->transform(new TrimTransformer());
        }

        return $this->bind($data);
    }

    public function getValue()
    {
        Assertion::true($this->errors->isEmpty(), 'Value cannot be retrieved when the form has errors');
        return $this->formData;
    }

    public function hasErrors() : bool
    {
        return !$this->errors->isEmpty();
    }

    public function getField(string $key) : Field
    {
        return new Field(
            $key,
            $this->data->getValue($key, ''),
            $this->errors->collect($key)
        );
    }
}
