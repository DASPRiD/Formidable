<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Constraint;

use Assert\Assertion;
use Litipk\BigNumbers\Decimal;
use Litipk\BigNumbers\DecimalConstants;
use function Assert\thatNullOr;

class StepNumberConstraint implements ConstraintInterface
{
    /**
     * @var Decimal
     */
    private $step;

    /**
     * @var Decimal
     */
    private $base;

    /**
     * @param int|float|string $step
     * @param int|float|string|null $base
     */
    public function __construct($step, $base = null)
    {
        Assertion::classExists(Decimal::class);
        Assertion::numeric($step);
        $decimalStep = Decimal::fromString((string) $step);

        Assertion::true($decimalStep->comp(DecimalConstants::zero()) > 0);
        thatNullOr($base)->numeric();

        $this->step = $decimalStep;
        $this->base = null === $base ? DecimalConstants::zero() : Decimal::fromString((string) $base);
    }

    public function __invoke($value) : ValidationResult
    {
        Assertion::numeric($value);
        $decimalValue = Decimal::fromString((string) $value);
        $floorModulo = $this->floorModulo($decimalValue->sub($this->base), $this->step);

        if ($floorModulo->comp(DecimalConstants::zero()) !== 0) {
            return new ValidationResult(new ValidationError('error.step-number', [
                'lowValue' => $this->trimZeroDecimal((string) $decimalValue->sub($floorModulo)),
                'highValue' => $this->trimZeroDecimal((string) $decimalValue->add($this->step)->sub($floorModulo)),
            ]));
        }

        return new ValidationResult();
    }

    private function floorModulo(Decimal $x, Decimal $y) : Decimal
    {
        return $x->sub($y->mul($x->div($y)->floor()));
    }

    private function trimZeroDecimal(string $x) : string
    {
        return '0' === $x ? '0' : rtrim(rtrim($x, '0'), '.');
    }
}
