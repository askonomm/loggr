<?php

namespace Asko\Loggr;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
enum Level: string
{
    case Emergency = "emergency";
    case Alert = "alert";
    case Critical = "critical";
    case Error = "error";
    case Warning = "warning";
    case Notice = "notice";
    case Info = "info";
    case Debug = "debug";
}
