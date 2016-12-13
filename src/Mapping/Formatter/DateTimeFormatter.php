<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Mapping\Formatter;

use DASPRiD\Formidable\Data;
use DASPRiD\Formidable\FormError\FormError;
use DASPRiD\Formidable\Mapping\BindResult;
use DASPRiD\Formidable\Mapping\Formatter\Exception\InvalidTypeException;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class DateTimeFormatter implements FormatterInterface
{
    /**
     * @var DateTimeZone
     */
    private $timeZone;

    /**
     * @var bool
     */
    private $localTime;

    public function __construct(DateTimeZone $timeZone, $localTime = false)
    {
        $this->timeZone = $timeZone;
        $this->localTime = $localTime;
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
            '(^
                (?<year>\d{4})-(?<month>\d{2})-(?<day>\d{2})[Tt]
                (?<hour>\d{2}):(?<minute>\d{2})(?::(?<second>\d{2})(?:\.(?<microsecond>\d{1,6}))?)?
                (?<timezone>[Zz]|[+-]\d{2}:\d{2})?
            $)x',
            $data->getValue($key),
            $matches
        )) {
            return BindResult::fromFormErrors(new FormError(
                $key,
                'error.date-time'
            ));
        }

        return BindResult::fromValue(DateTimeImmutable::createFromFormat(
            '!Y-m-d\TH:i:s.u' . ($this->localTime ? '' : 'P'),
            sprintf(
                '%s-%s-%sT%s:%s:%s.%s%s',
                $matches['year'],
                $matches['month'],
                $matches['day'],
                $matches['hour'],
                $matches['minute'],
                !empty($matches['second']) ? $matches['second'] : '00',
                !empty($matches['microsecond']) ? $matches['microsecond'] : '00',
                $matches['timezone'] ?? ''
            ),
            $this->timeZone
        )->setTimezone($this->timeZone));
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(string $key, $value) : Data
    {
        if (!$value instanceof DateTimeInterface) {
            throw InvalidTypeException::fromInvalidType($value, 'DateTimeInterface');
        }

        $dateTime = $value->setTimezone($this->timeZone);
        $timeZoneFlag = $this->localTime ? '' : 'P';

        if ((int) $dateTime->format('u') > 0) {
            return Data::fromFlatArray([$key => $dateTime->format('Y-m-d\TH:i:s.u' . $timeZoneFlag)]);
        }

        return Data::fromFlatArray([$key => $dateTime->format('Y-m-d\TH:i:s' . $timeZoneFlag)]);
    }
}
