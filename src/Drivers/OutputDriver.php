<?php

namespace Asko\Loggr\Drivers;

use Asko\Loggr\Driver;

class OutputDriver implements Driver
{
    /**
     * Writes the provided serialized message to the standard output stream.
     *
     * @param string $serializedMessage The message to be logged, in a serialized format.
     * @return void
     */
    public function log(string $serializedMessage): void
    {
        file_put_contents('php://output', $serializedMessage . PHP_EOL);
    }
}
