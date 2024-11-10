<?php

namespace Asko\Loggr;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
readonly class Message
{
    public function __construct(
        public Level $level,
        public array $trace = [],
        public mixed $context = null,
    ) {}
}