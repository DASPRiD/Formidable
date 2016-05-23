<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

use Assert\Assertion;
use DASPRiD\Formidable\FormError\FormError;
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
    private $data;

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
        $this->data = Data::none();
        $this->errors = new FormErrorSequence();
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
            $form->errors = $bindResult->getFormErrorSequence();
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

    public function withError(FormError $formError) : self
    {
        $form = clone $this;
        $form->errors = $form->errors->merge(new FormErrorSequence($formError));
        return $form;
    }

    public function withGlobalError(string $message, array $arguments = []) : self
    {
        return $this->withError(new FormError('', $message, $arguments));
    }

    public function getValue()
    {
        Assertion::true($this->errors->isEmpty(), 'Value cannot be retrieved when the form has errors');
        return $this->value;
    }

    public function hasErrors() : bool
    {
        return !$this->errors->isEmpty();
    }

    public function hasGlobalErrors() : bool
    {
        return !$this->getGlobalErrors()->isEmpty();
    }

    public function getGlobalErrors() : FormErrorSequence
    {
        return $this->errors->collect('');
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
