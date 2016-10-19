<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable;

use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use Psr\Http\Message\ServerRequestInterface;

interface FormInterface
{
    public function fill($formData) : self;

    public function bind(Data $data) : self;

    public function bindFromRequest(ServerRequestInterface $request, bool $trimData = true) : self;

    public function withError(FormError $formError) : self;

    public function withGlobalError(string $message, array $arguments = []) : self;

    public function getValue();

    public function hasErrors() : bool;

    public function getErrors() : FormErrorSequence;

    public function hasGlobalErrors() : bool;

    public function getGlobalErrors() : FormErrorSequence;

    public function getField(string $key) : Field;
}
