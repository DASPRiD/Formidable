<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidLimitException;
use DASPRiD\Formidable\Mapping\Constraint\Exception\InvalidTypeException;
use DASPRiD\Formidable\Mapping\Constraint\Exception\MissingDecimalDependencyException;
use Litipk\BigNumbers\Decimal;

final class MinNumberConstraint implements ConstraintInterface
{
    /**
     * @var Decimal
     */
    private $limit;

    /**
     * @param int|float|string $limit
     */
    public function __construct($limit)
    {
        if (!class_exists(Decimal::class)) {
            // @codeCoverageIgnoreStart
            throw MissingDecimalDependencyException::fromMissingDependency();
            // @codeCoverageIgnoreEnd
        }

        if (!is_numeric($limit)) {
            throw InvalidLimitException::fromNonNumericValue($limit);
        }

        $this->limit = Decimal::fromString((string) $limit);
    }

    public function __invoke($value) : ValidationResult
    {
        if (!is_numeric($value)) {
            throw InvalidTypeException::fromNonNumericValue($value);
        }

        $decimalValue = Decimal::fromString((string) $value);

        if ($decimalValue->comp($this->limit) === -1) {
            return new ValidationResult(new ValidationError('error.min-number', ['limit' => (string) $this->limit]));
        }

        return new ValidationResult();
    }
}
