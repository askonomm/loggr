<?php

namespace Asko\Loggr;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
interface Driver
{
    public function log(string $serializedMessage): void;
}