<?php

namespace Asko\Loggr\Tests;

use DateTime;
use Asko\Loggr\Drivers\OutputDriver;
use Asko\Loggr\Loggr;
use PHPUnit\Framework\TestCase;

class OutputDriverTest extends TestCase
{
    public function testEmergency(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.EMERGENCY: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->emergency('test');
    }

    public function testAlert(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.ALERT: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->alert('test');
    }

    public function testCritical(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.CRITICAL: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->critical('test');
    }

    public function testError(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.ERROR: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->error('test');
    }

    public function testWarning(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.WARNING: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->warning('test');
    }

    public function testNotice(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.NOTICE: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->notice('test');
    }

    public function testInfo(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.INFO: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->info('test');
    }

    public function testDebug(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.DEBUG: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->debug('test');
    }

    public function testLog(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[$date] OutputDriverTest.DEBUG: test" . PHP_EOL);
        $logger = new Loggr(new OutputDriver());
        $logger->log('debug', 'test');
    }
}
