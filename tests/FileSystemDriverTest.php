<?php

namespace Asko\Loggr\Tests;

use DateTime;
use Asko\Loggr\Drivers\FileSystemDriver;
use Asko\Loggr\Loggr;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class FileSystemDriverTest extends MockeryTestCase
{
    public function testEmergency(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->emergency('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.EMERGENCY: test", $data);
    }

    public function testAlert(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->alert('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.ALERT: test", $data);
    }

    public function testCritical(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->critical('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.CRITICAL: test", $data);
    }

    public function testError(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->error('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.ERROR: test", $data);
    }

    public function testWarning(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->warning('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.WARNING: test", $data);
    }

    public function testNotice(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->notice('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.NOTICE: test", $data);
    }

    public function testInfo(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->info('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.INFO: test", $data);
    }

    public function testDebug(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->debug('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.DEBUG: test", $data);
    }

    public function testMultipleEntries(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->emergency('test1');
        $loggr->alert('test2');
        $loggr->critical('test3');
        $loggr->error('test4');
        $loggr->warning('test5');
        $loggr->notice('test6');
        $loggr->info('test7');
        $loggr->debug('test8');

        $lines = array_filter(explode("\n", $data), fn($line) => !empty($line));
        $this->assertCount(8, $lines);

        $first_line = $lines[0];
        $last_line = $lines[count($lines) - 1];
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.EMERGENCY: test", $first_line);
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.DEBUG: test", $last_line);
    }

    private function mockLogger(string &$result): Loggr
    {
        $driver = Mockery::mock(FileSystemDriver::class, ['/tmp'])->makePartial();
        $driver->shouldReceive('log')->passthru();
        $driver->shouldReceive('createLogFileIfNotExist')->andReturn(true);
        $driver->shouldReceive('write')->withArgs(function (string $path, string $data) use (&$result) {
            $result .= $data;

            return true;
        });

        return new Loggr($driver);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}