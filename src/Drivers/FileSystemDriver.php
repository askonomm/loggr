<?php

namespace Asko\Loggr\Drivers;

use Asko\Loggr\Driver;
use DateTime;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
readonly class FileSystemDriver implements Driver
{
    public function __construct(
        private string $directory,
    ) {}

    /**
     * Logs a serialized message to a file. The log file is created on a daily basis.
     * If the required directory or log file does not exist, they are created.
     * Ensures that the log file is writable before attempting to write the message.
     *
     * @param string $serializedMessage The message to be logged, serialized as a string.
     * @return void
     * @throws \RuntimeException If the log file or directory cannot be created, or if the log file is not writable.
     */
    public function log(string $serializedMessage): void
    {
        // If there's no parent directory, try creating one
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0600, true);
        }

        $date = new DateTime();
        $file_name = "{$date->format('Y-m-d')}.log";
        $path = $this->directory . DIRECTORY_SEPARATOR . $file_name;

        // If the log file does not exist, try creating one
        if (!file_exists($path) && !touch($path)) {
            throw new \RuntimeException('Log file could not be created at path: ' . $path);
        }

        // We managed to get this far, but still can't write to the log file
        if (!is_writeable($path)) {
            throw new \RuntimeException('Log file is not writeable at path: ' . $path);
        }

        file_put_contents(
            filename: $path,
            data: $serializedMessage . PHP_EOL,
            flags: FILE_APPEND
        );
    }
}