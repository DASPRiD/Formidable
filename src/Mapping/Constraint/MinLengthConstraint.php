<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLengthException;
use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidTypeException;

final class MinLengthConstraint implements ConstraintInterface
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
        if ($lengthLimit < 0) {
            throw InvalidLengthException::fromNegativeLength($lengthLimit);
        }

        $this->lengthLimit = $lengthLimit;
        $this->encoding = $encoding;
    }

    public function __invoke($value) : ValidationResult
    {
        if (!is_string($value)) {
            throw InvalidTypeException::fromInvalidType($value, 'string');
        }

        if (iconv_strlen($value, $this->encoding) < $this->lengthLimit) {
            return new ValidationResult(new ValidationError('error.min-length', ['lengthLimit' => $this->lengthLimit]));
        }

        return new ValidationResult();
    }
}
