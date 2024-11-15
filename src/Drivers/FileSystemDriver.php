<?php

namespace Asko\Loggr\Drivers;

use Asko\Loggr\Driver;
use DateTime;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
class FileSystemDriver implements Driver
{
    public function __construct(
        private readonly string $directory,
    ) {}

    /**
     * Logs a serialized message to a file. The log file is created on a daily basis.
     * If the required directory or log file does not exist, they are created.
     * Ensures that the log file is writable before attempting to write the message.
     *
     * @param string $serializedMessage The message to be logged, serialized as a string.
     * @return void
     */
    public function log(string $serializedMessage): void
    {
        $date = new DateTime();
        $file_name = "{$date->format('Y-m-d')}.log";
        $path = $this->directory . DIRECTORY_SEPARATOR . $file_name;

        $this->createLogFileIfNotExist($path);
        $this->write($path, $serializedMessage . PHP_EOL);
    }

    public function createLogFileIfNotExist(string $path): void
    {
        if (!file_exists($this->directory)) {
            mkdir($this->directory, 0600, true);
        }

        if (is_writable($this->directory)) {
            touch($path);
        }
    }

    public function write(string $path, string $data): void
    {
        file_put_contents(
            filename: $path,
            data: $data,
            flags: FILE_APPEND
        );
    }
}