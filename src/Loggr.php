<?php

namespace Asko\Loggr;

/**
 * Loggr is an extendable logging utility class brought to you by the frustration of
 * every logging class always having its own unique format, making debugging difficult.
 *
 * Instead of having its own yet-another-format that no tool supports, Loggr attempts to match
 * many already existing formats, allowing you to use whichever you prefer most.
 *
 * @author Asko Nõmm <asko@faultd.com>
 */
class Loggr
{
    private array $trace;

    public function __construct(
        public ?Driver $driver = null,
        public ?Format $format = Format::Laravel,
    ){}

    /**
     * Logs a message at a specified level with optional context.
     *
     * @param Level $level The severity level of the log message. Defaults to Level::Info.
     * @param mixed $context Additional data or context to include with the log message. Optional.
     * @return void
     * @throws \Exception
     */
    private function log(Level $level = Level::Info, mixed $context = null): void
    {
        if (!$this->driver) {
            throw new \Exception("No driver has been set.");
        }

        if (!$this->format) {
            throw new \Exception("No format has been set.");
        }

        $this->driver->log($this->format->serialize(new Message(
            level: $level,
            trace: $this->trace,
            context: $context,
        )));
    }

    /**
     * Logs an emergency level message with optional context.
     *
     * @param mixed $context Additional data or context to include with the emergency message. Optional.
     * @return void
     * @throws \Exception
     */
    public function emergency(mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Emergency, $context);
    }

    /**
     * Sends an alert-level log message with optional context.
     *
     * @param mixed $context Additional data or context to include with the log message. Optional.
     * @return void
     * @throws \Exception
     */
    public function alert(mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Alert, $context);
    }

    /**
     * Logs a critical level message with optional context.
     *
     * @param mixed $context Additional data or context to include with the log message. Optional.
     * @return void
     * @throws \Exception
     */
    public function critical( mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Critical, $context);
    }

    /**
     * Logs an error message with optional context.
     *
     * @param mixed $context Additional data or context to include with the error message. Optional.
     * @return void
     * @throws \Exception
     */
    public function error(mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Error, $context);
    }

    /**
     * Logs a warning message with the specified context.
     *
     * @param mixed $context Optional context information to include in the log.
     * @return void
     * @throws \Exception
     */
    public function warning(mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Warning, $context);
    }

    /**
     * Logs a notice message with the specified context.
     *
     * @param mixed $context Optional context information to include in the log.
     * @return void
     * @throws \Exception
     */
    public function notice(mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Notice, $context);
    }

    /**
     * Logs an informational message with the specified context.
     *
     * @param mixed $context Optional context information to include in the log.
     * @return void
     * @throws \Exception
     */
    public function info(mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Info, $context);
    }

    /**
     * Logs a debug message with the specified context.
     *
     * @param mixed $context Optional context information to include in the log.
     * @return void
     * @throws \Exception
     */
    public function debug(mixed $context = null): void
    {
        $this->trace = debug_backtrace()[0];
        $this->log(Level::Debug, $context);
    }
}