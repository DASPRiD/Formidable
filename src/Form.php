<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

use DASPRiD\Formidable\Mapping\ObjectMapping;
use Psr\Http\Message\ServerRequestInterface;

final class Form
{
    /**
     * @var ObjectMapping
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

    public function __construct(ObjectMapping $mapping)
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
        $form->value = $this->mapping->bind($data);
        return $form;
    }

    public function bindFromRequest(ServerRequestInterface $request) : self
    {
        if ('POST' === $request->getMethod()) {
            $data = new Data($request->getParsedBody());
        } else {
            $data = new Data($request->getQueryParams());
        }

        return $this->bind($data);
    }

    public function get()
    {
        return $this->formData;
    }

    public function field(string $key) : Field
    {
        $fieldValue = null;

        if (array_key_exists($key, $this->data)) {
            $fieldValue = $this->data['key'];
        }

        $fieldErrors = [];

        return new Field($key, $fieldValue, $fieldErrors);
    }
}
