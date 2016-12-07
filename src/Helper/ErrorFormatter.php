<?php
declare(strict_types = 1);

namespace DASPRiD\Formidable\Helper;

use Assert\Assertion;
use DASPRiD\Formidable\Helper\Exception\NonExistentMessage;
use MessageFormatter;

final class ErrorFormatter
{
    const BUILD_IN_MESSAGES = [
        'error.required' => 'This field is required',
        'error.empty' => 'Value must not be empty',
        'error.integer' => 'Integer value expected',
        'error.float' => 'Float value expected',
        'error.boolean' => 'Boolean value expected',
        'error.date' => 'Date value expected',
        'error.time' => 'Time value expected',
        'error.date-time' => 'Datetime value expected',
        'error.email-address' => 'Valid email address required',
        'error.min-length' => 'Minimum length is {lengthLimit, plural, one {# character} other {# characters}}',
        'error.max-length' => 'Maximum length is {lengthLimit, plural, one {# character} other {# characters}}',
        'error.min-number' => 'Minimum value is {limit, number}',
        'error.max-number' => 'Maximum value is {limit, number}',
        'error.step-number' => 'Value is invalid, closest valid values are {lowValue, number} and {highValue, number}',
    ];

    /**
     * @var string[]
     */
    private $messages = [];

    public function __construct(array $messages = [])
    {
        Assertion::classExists(MessageFormatter::class);
        $this->messages = array_replace(self::BUILD_IN_MESSAGES, $messages);
    }

    public function __invoke(string $key, array $arguments = []) : string
    {
        if (!array_key_exists($key, $this->messages)) {
            throw NonExistentMessage::fromNonExistentMessageKey($key);
        }

        return MessageFormatter::formatMessage('en-US', $this->messages[$key], $arguments);
    }
}
