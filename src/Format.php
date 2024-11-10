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
    public function serialize(Message $message): string
    {
        return match($this) {
            self::JSON => $this->serializeJson($message),
            self::IntelliJ => $this->serializeIntelliJ($message),
            self::Laravel => $this->serializeLaravel($message),
            self::Symfony => $this->serializeSymfony($message),
        };
    }

    /**
     * Serializes the given message into JSON format.
     *
     * @param Message $message The message to be serialized.
     * @return string The JSON-encoded message.
     */
    private function serializeJson(Message $message): string
    {
        return json_encode([
            'date' => (new DateTime)->format('Y-m-d H:i:s'),
            'level' => $message->level->value,
            'context' => $message->context,
            'trace' => [
                'file' => $message->trace['file'],
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
    private function serializeLaravel(Message $message): string
    {
        // Date
        $date = (new DateTime)->format('Y-m-d H:i:s');
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
    private function serializeSymfony(Message $message): string
    {
        // Date
        $date = (new DateTime)->format('Y-m-d\TH:i:s.uP');
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
    private function serializeIntelliJ(Message $message): string
    {
        // Date
        $date = (new DateTime)->format('Y-m-d H:i:s');
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
