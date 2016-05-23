<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use Assert\Assertion;

class MinLengthConstraint implements ConstraintInterface
{
    /**
     * @var int
     */
    private $lengthLimit;

    /**
     * @var string
     */
    private $encoding;

    public function __construct(int $lengthLimit, $encoding = 'utf-8')
    {
        Assertion::greaterOrEqualThan($lengthLimit, 0);

        $this->lengthLimit = $lengthLimit;
        $this->encoding = $encoding;
    }

    public function __invoke($value) : ValidationResult
    {
        Assertion::string($value);

        if (iconv_strlen($value, $this->encoding) < $this->lengthLimit) {
            return new ValidationResult(new ValidationError('error.min-length', ['lengthLimit' => $this->lengthLimit]));
        }

        return new ValidationResult();
    }
}
