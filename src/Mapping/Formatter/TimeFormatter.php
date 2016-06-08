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

final class TimeFormatter implements FormatterInterface
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

        // Technically, seconds must always be present, according to the spec, but at least Chrome seems to ommit them.
        if (!preg_match(
            '(^(?<hour>\d{2}):(?<minute>\d{2})(?::(?<second>\d{2})(?:\.(?<microsecond>\d{1,6}))?)?$)',
            $data->getValue($key),
            $matches
        )) {
            return BindResult::fromFormErrors(new FormError(
                $key,
                'error.time'
            ));
        }

        return BindResult::fromValue(DateTimeImmutable::createFromFormat(
            '!H:i:s.u',
            sprintf(
                '%s:%s:%s.%s',
                $matches['hour'],
                $matches['minute'],
                $matches['second'] ?? '00',
                $matches['microsecond'] ?? '0'
            ),
            $this->timeZone
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        Assertion::isInstanceOf($value, DateTimeInterface::class);
        $dateTime = $value->setTimezone($this->timeZone);

        if ((int) $dateTime->format('u') > 0) {
            return Data::fromFlatArray([$key => $dateTime->format('H:i:s.u')]);
        }

        return Data::fromFlatArray([$key => $dateTime->format('H:i:s')]);
    }
}
