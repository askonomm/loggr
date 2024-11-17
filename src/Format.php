<?php

namespace Asko\Loggr;

use DateTime;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
enum Format
{
    case JSON;
    case IntelliJ;
    case Laravel;
    case Symfony;

    /**
     * Serializes the given message into a format based on the type of serialization.
     *
     * @param Message $message The message to be serialized.
     * @return string The serialized message.
     */
    public function serialize(Message $message, DateTime $dateTime = new DateTime): string
    {
        return match($this) {
            self::JSON => $this->serializeJson($message, $dateTime),
            self::IntelliJ => $this->serializeIntelliJ($message, $dateTime),
            self::Laravel => $this->serializeLaravel($message, $dateTime),
            self::Symfony => $this->serializeSymfony($message, $dateTime),
        };
    }

    /**
     * Serializes the given message into JSON format.
     *
     * @param Message $message The message to be serialized.
     * @return string The JSON-encoded message.
     */
    private function serializeJson(Message $message, DateTime $dateTime): string
    {
        /** @var string $trace_file */
        $trace_file = $message->trace['file'];
        /** @var string $trace_line */
        $trace_line = $message->trace['line'];

        $json = json_encode([
            'date' => $dateTime->format('Y-m-d H:i:s'),
            'level' => strtoupper($message->level->value),
            'message' => $message->content,
            'context' => $message->context,
            'trace' => [
                'file' => pathinfo($trace_file, PATHINFO_FILENAME),
                'line' => $trace_line,
            ],
        ]);

        if (!$json) {
            return '';
        }

        return $json;
    }

    /**
     * Serializes the given message into a Laravel log format.
     *
     * @param Message $message The message to be serialized.
     * @return string The serialized message.
     */
    private function serializeLaravel(Message $message, DateTime $dateTime): string
    {
        /** @var string $trace_file */
        $trace_file = $message->trace['file'];

        $datetime = $dateTime->format('Y-m-d H:i:s');
        $filename = pathinfo($trace_file, PATHINFO_FILENAME);
        $level = strtoupper($message->level->value);
        $content = !empty($message->content) ? "$message->content " : "";
        $context =  isset($message->context) ? json_encode($message->context) : '';

        return trim("[$datetime] $filename.$level: $content$context");
    }

    /**
     * Serialized the given message into a Symfony log format.
     *
     * @param Message $message The message object that contains log details.
     * @return string The formatted log string.
     */
    private function serializeSymfony(Message $message, DateTime $dateTime): string
    {
        /** @var string $trace_file */
        $trace_file = $message->trace['file'];

        // Date
        $datetime = $dateTime->format('Y-m-d\TH:i:s.uP');
        $filename = pathinfo($trace_file, PATHINFO_FILENAME);
        $level = strtoupper($message->level->value);
        $content = !empty($message->content) ? "$message->content " : "";
        $context =  isset($message->context) ? json_encode($message->context) : '';

        return trim("[$datetime] $filename.$level: $content$context");
    }

    /**
     * Serializes the given message into a IntelliJ log format.
     *
     * @param Message $message The message object that contains log details.
     * @return string The formatted log string.
     */
    private function serializeIntelliJ(Message $message, DateTime $dateTime): string
    {
        /** @var string $trace_file */
        $trace_file = $message->trace['file'];
        /** @var string $trace_line */
        $trace_line = $message->trace['line'];

        // Date
        $datetime = $dateTime->format('Y-m-d H:i:s');
        $level = strtoupper($message->level->value);
        $filename = pathinfo($trace_file, PATHINFO_FILENAME);
        $content = !empty($message->content) ? "$message->content " : "";
        $context =  isset($message->context) ? json_encode($message->context) : '';

        return trim("$datetime [$trace_line] $level - $filename - $content$context");
    }
}
