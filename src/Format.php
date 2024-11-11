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
    public function serialize(Message $message, ?DateTime $dateTime = new DateTime): string
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
        return json_encode([
            'date' => $dateTime->format('Y-m-d H:i:s'),
            'level' => $message->level->value,
            'context' => $message->context,
            'trace' => [
                'file' => pathinfo($message->trace['file'], PATHINFO_FILENAME),
                'line' => $message->trace['line'],
            ],
        ]);
    }

    /**
     * Serializes the given message into a Laravel log format.
     *
     * @param Message $message The message to be serialized.
     * @return string The serialized message.
     */
    private function serializeLaravel(Message $message, DateTime $dateTime): string
    {
        // Date
        $date = $dateTime->format('Y-m-d H:i:s');
        $line = "[$date] ";

        // File name and level
        $filename = pathinfo($message->trace['file'], PATHINFO_FILENAME);
        $line .= "{$filename}.{$message->level->value}: ";

        // Context
        if (is_array($message->context) || is_object($message->context)) {
            $line .= json_encode($message->context);
        } else {
            $line .= $message->context;
        }

        return $line;
    }

    /**
     * Serialized the given message into a Symfony log format.
     *
     * @param Message $message The message object that contains log details.
     * @return string The formatted log string.
     */
    private function serializeSymfony(Message $message, DateTime $dateTime): string
    {
        // Date
        $date = $dateTime->format('Y-m-d\TH:i:s.uP');
        $line = "[$date] ";

        // File name and level
        $filename = pathinfo($message->trace['file'], PATHINFO_FILENAME);
        $line .= "{$filename}.{$message->level->value}: ";

        // Context
        if (is_array($message->context) || is_object($message->context)) {
            $line .= json_encode($message->context);
        } else {
            $line .= $message->context;
        }

        return $line;
    }

    /**
     * Serializes the given message into a IntelliJ log format.
     *
     * @param Message $message The message object that contains log details.
     * @return string The formatted log string.
     */
    private function serializeIntelliJ(Message $message, DateTime $dateTime): string
    {
        // Date
        $date = $dateTime->format('Y-m-d H:i:s');
        $line = "{$date} ";

        // Line number
        $line .= "[{$message->trace['line']}] ";

        // Level
        $line .= "{$message->level->value} ";

        // Filename
        $filename = pathinfo($message->trace['file'], PATHINFO_FILENAME);
        $line .= "- {$filename} - ";

        // Context
        if (is_array($message->context) || is_object($message->context)) {
            $line .= json_encode($message->context);
        } else {
            $line .= $message->context;
        }

        return $line;
    }
}
