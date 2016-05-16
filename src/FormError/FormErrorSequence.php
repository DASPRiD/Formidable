<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\FormError;

use Countable;
use IteratorAggregate;
use Traversable;

final class FormErrorSequence implements Countable, IteratorAggregate
{
    /**
     * @var FormError[]
     */
    private $formErrors = [];

    /**
     * @param FormError[] $formErrors
     */
    public function __construct(array $formErrors)
    {
        foreach ($formErrors as $formError) {
            if (!$formError instanceof FormError) {
                // @todo throw exception
            }

            $this->formErrors[] = $formError;
        }
    }

    public function merge(self $other)
    {
        return new self(array_merge($this->formErrors, $other->formErrors));
    }

    public function count() : int
    {
        return count($this->formErrors);
    }

    public function getIterator() : Traversable
    {
        yield from $this->formErrors;
    }
}
