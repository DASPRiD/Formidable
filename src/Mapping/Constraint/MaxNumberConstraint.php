<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use Assert\Assertion;
use Litipk\BigNumbers\Decimal;

class MaxNumberConstraint implements ConstraintInterface
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
        Assertion::classExists(Decimal::class);
        Assertion::numeric($limit);
        $this->limit = Decimal::fromString((string) $limit);
    }

    public function __invoke($value) : ValidationResult
    {
        Assertion::numeric($value);
        $decimalValue = Decimal::fromString((string) $value);

        if ($decimalValue->comp($this->limit) === 1) {
            return new ValidationResult(new ValidationError('error.max-number', ['limit' => (string) $this->limit]));
        }

        return new ValidationResult();
    }
}
