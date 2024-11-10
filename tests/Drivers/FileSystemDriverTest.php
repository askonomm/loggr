<?php

namespace Asko\Loggr\Tests\Drivers;

use Asko\Loggr\Drivers\FileSystemDriver;
use Asko\Loggr\Loggr;
use DateTime;
use PHPUnit\Framework\TestCase;

class FileSystemDriverTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testEmergency(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->emergency('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.EMERGENCY: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testAlert(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->alert('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.ALERT: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testCritical(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->critical('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.CRITICAL: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testError(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->error('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.ERROR: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testWarning(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->warning('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.WARNING: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testNotice(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->notice('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.NOTICE: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testInfo(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->info('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.INFO: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testDebug(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->debug('test');
        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.DEBUG: test", $last_line);
    }

    /**
     * @throws \Exception
     */
    public function testMultipleEntries(): void
    {
        $date = new DateTime();
        $loggr = new Loggr(new FileSystemDriver(__DIR__ . '/logs'));
        $loggr->emergency('test');
        $loggr->alert('test');
        $loggr->critical('test');
        $loggr->error('test');
        $loggr->warning('test');
        $loggr->notice('test');
        $loggr->info('test');
        $loggr->debug('test');

        $lines = file(__DIR__ . '/logs/' . $date->format('Y-m-d') . '.log');
        $this->assertCount(8, $lines);

        $first_line = $lines[0];
        $last_line = end($lines);
        $full_date = $date->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.EMERGENCY: test", $first_line);
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.DEBUG: test", $last_line);
    }

    public function tearDown(): void
    {
        array_map('unlink', glob(__DIR__ . '/logs/*'));
        rmdir(__DIR__ . '/logs');
    }
}