<?php

namespace Asko\Loggr;

/**
 * @author Asko Nõmm <asko@faultd.com>
 */
enum Level: string
{
    case Emergency = "EMERGENCY";
    case Alert = "ALERT";
    case Critical = "CRITICAL";
    case Error = "ERROR";
    case Warning = "WARNING";
    case Notice = "NOTICE";
    case Info = "INFO";
    case Debug = "DEBUG";
}
