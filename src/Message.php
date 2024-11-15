<?php

namespace Asko\Loggr;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
readonly class Message
{
    public function __construct(
        public Level $level,
        /** @var array<string, mixed> $trace */
        public array $trace = [],
        public string $content = '',
        public mixed $context = null,
    ) {}
}