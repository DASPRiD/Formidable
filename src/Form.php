<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

use DASPRiD\Formidable\Exception\InvalidDataException;
use DASPRiD\Formidable\Exception\UnboundDataException;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Mapping\MappingInterface;
use DASPRiD\Formidable\Transformer\TrimTransformer;
use Psr\Http\Message\ServerRequestInterface;

final class Form implements FormInterface
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

    public function fill($formData) : FormInterface
    {
        $form = clone $this;
        $form->data = $this->mapping->unbind($formData);
        $form->value = $formData;
        return $form;
    }

    public function withDefaults(Data $data) : FormInterface
    {
        $form = clone $this;
        $form->data = $data;

        return $form;
    }

    public function bind(Data $data) : FormInterface
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

    public function bindFromRequest(ServerRequestInterface $request, bool $trimData = true) : FormInterface
    {
        if ('POST' === $request->getMethod()) {
            $data = Data::fromNestedArray($request->getParsedBody());
        } elseif (in_array($request->getMethod(), ['PUT', 'PATCH'])) {
            parse_str((string) $request->getBody(), $rawData);
            $data = Data::fromNestedArray($rawData);
        } else {
            $data = Data::fromNestedArray($request->getQueryParams());
        }

        if ($trimData) {
            $data = $data->transform(new TrimTransformer());
        }

        return $this->bind($data);
    }

    public function withError(FormError $formError) : FormInterface
    {
        $form = clone $this;
        $form->errors = $form->errors->merge(new FormErrorSequence($formError));
        return $form;
    }

    public function withGlobalError(string $message, array $arguments = []) : FormInterface
    {
        return $this->withError(new FormError('', $message, $arguments));
    }

    public function getValue()
    {
        if (!$this->errors->isEmpty()) {
            throw InvalidDataException::fromGetValueAttempt();
        }

        if (null === $this->value) {
            throw UnboundDataException::fromGetValueAttempt();
        }

        return $this->value;
    }

    public function hasErrors() : bool
    {
        return !$this->errors->isEmpty();
    }

    public function getErrors() : FormErrorSequence
    {
        return $this->errors;
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
            $this->errors->collect($key),
            $this->data
        );
    }
}
