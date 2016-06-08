<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use Assert\Assertion;
use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\Mapping\BindResult;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class DateFormatter implements FormatterInterface
{
    /**
     * @var DateTimeZone
     */
    private $timeZone;

    public function __construct(DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $key, Data $data) : BindResult
    {
        if (!$data->hasKey($key)) {
            return BindResult::fromFormErrors(new FormError(
                $key,
                'error.required'
            ));
        }

        $dateTime = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $data->getValue($key),
            $this->timeZone
        );

        if (false === $dateTime) {
            return BindResult::fromFormErrors(new FormError(
                $key,
                'error.date'
            ));
        }

        return BindResult::fromValue($dateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        Assertion::isInstanceOf($value, DateTimeInterface::class);
        $dateTime = $value->setTimezone($this->timeZone);

        return Data::fromFlatArray([$key => $dateTime->format('Y-m-d')]);
    }
}
