<?php

namespace Asko\Loggr;

use Psr\Log\LoggerInterface;

/**
 * Loggr is an extendable logging utility class brought to you by the frustration of
 * every logging class always having its own unique format, making debugging difficult.
 *
 * Instead of having its own yet-another-format that no tool supports, Loggr attempts to match
 * many already existing formats, allowing you to use whichever you prefer most.
 *
 * @author Asko Nõmm <asko@faultd.com>
 */
class Loggr implements LoggerInterface
{
    /** @var array<string, mixed> $trace */
    private array $trace;

    /**
     * Since it would suck if a logging utility throws an exception,
     * Loggr instead of throwing will simply set the error here, in case
     * logging doesn't work, and you might want to find out why.
     *
     * @var string|null $error
     */
    public ?string $error = null;

    public function __construct(
        public ?Driver $driver = null,
        public ?Format $format = Format::Laravel,
    ){}

    /**
     * Logs a message at a specified level with optional context.
     *
     * @param Level $level The severity level of the log message. Defaults to Level::Info.
     * @param string $message The log message.
     * @param mixed $context Additional data or context to include with the log message. Optional.
     * @return void
     */
    private function write(Level $level, string $message, mixed $context = null): void
    {
        if (!$this->driver || !$this->format) {
            $this->error = "Driver or format not set.";
            return;
        }

        $this->driver->log($this->format->serialize(new Message(
            level: $level,
            trace: $this->trace,
            content: $this->interpolate($message, $context),
            context: $context,
        )));
    }

    /**
     * @param string $message
     * @param mixed|null $context
     * @return string
     */
    private function interpolate(string $message, mixed $context = null): string
    {
        // $context has to be present, an array or an object.
        if (!is_array($context) && !is_object($context)) return $message;

        /** @var string|null $parsed_message */
        $parsed_message = preg_replace_callback('/{(?<var>.*?)}/', function ($matches) use ($context) {
            return $this->parseInterpolation($matches['var'], $context);
        }, $message);

        return $parsed_message ?? '';
    }

    /**
     * @param string $var
     * @param mixed|null $context
     * @return string
     */
    private function parseInterpolation(string $var, mixed $context = null): string
    {
        $parts = explode('.', $var);
        $value = $context;

        foreach ($parts as $part) {
            if (is_array($value) && array_key_exists($part, $value)) {
                $value = $value[$part];
                continue;
            }

            if (is_object($value) && property_exists($value, $part)) {
                $value = $value->{$part};
                continue;
            }

            return '';
        }

        return match(gettype($value)) {
            'string' => $value,
            'integer', 'boolean', 'double' => (string)$value,
            'NULL' => 'null',
            default => ''
        };
    }

    /**
     * Logs an emergency level message with optional context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Additional data or context to include with the emergency message. Optional.
     * @return void
     */
    public function emergency(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Emergency, $message, $context);
    }

    /**
     * Sends an alert-level log message with optional context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Additional data or context to include with the log message. Optional.
     * @return void
     */
    public function alert(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Alert, $message, $context);
    }

    /**
     * Logs a critical level message with optional context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Additional data or context to include with the log message. Optional.
     * @return void
     */
    public function critical(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Critical, $message, $context);
    }

    /**
     * Logs an error message with optional context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Additional data or context to include with the error message. Optional.
     * @return void
     */
    public function error(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Error, $message, $context);
    }

    /**
     * Logs a warning message with the specified context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Optional context information to include in the log.
     * @return void
     */
    public function warning(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Warning, $message, $context);
    }

    /**
     * Logs a notice message with the specified context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Optional context information to include in the log.
     * @return void
     */
    public function notice(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Notice, $message, $context);
    }

    /**
     * Logs an informational message with the specified context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Optional context information to include in the log.
     * @return void
     */
    public function info(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Info, $message, $context);
    }

    /**
     * Logs a debug message with the specified context.
     *
     * @param string|\Stringable $message
     * @param mixed $context Optional context information to include in the log.
     * @return void
     */
    public function debug(string|\Stringable $message, mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->write(Level::Debug, $message, $context);
    }

    /**
     * Logs a message with the specified level and context.
     *
     * @param mixed $level The logging level (e.g., error, info, debug).
     * @param \Stringable|string $message The message to log.
     * @param mixed $context Optional context information to include in the log.
     * @return void
     */
    public function log(mixed $level, \Stringable|string $message, mixed $context = null): void
    {
        if (!is_string($level) && !is_int($level)) return;

        $this->trace = debug_backtrace()[0];
        $this->write(Level::from($level), $message, $context);
    }
}