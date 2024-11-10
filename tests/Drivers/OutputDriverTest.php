<?php

namespace Asko\Loggr\Tests\Drivers;

use Asko\Loggr\Drivers\OutputDriver;
use Asko\Loggr\Loggr;
use DateTime;
use PHPUnit\Framework\TestCase;

class OutputDriverTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testEmergency(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.EMERGENCY: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->emergency('test');
    }

    /**
     * @throws \Exception
     */
    public function testAlert(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.ALERT: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->alert('test');
    }

    /**
     * @throws \Exception
     */
    public function testCritical(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.CRITICAL: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->critical('test');
    }

    /**
     * @throws \Exception
     */
    public function testError(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.ERROR: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->error('test');
    }

    /**
     * @throws \Exception
     */
    public function testWarning(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.WARNING: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->warning('test');
    }

    /**
     * @throws \Exception
     */
    public function testNotice(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.NOTICE: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->notice('test');
    }

    /**
     * @throws \Exception
     */
    public function testInfo(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.INFO: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test');
    }

    /**
     * @throws \Exception
     */
    public function testDebug(): void
    {
        $date = (new DateTime)->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$date}] OutputDriverTest.DEBUG: test");
        $loggr = new Loggr(new OutputDriver());
        $loggr->debug('test');
    }
}