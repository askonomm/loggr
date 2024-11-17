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
        $logger = $this->mockLogger($data);
        $logger->emergency('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.EMERGENCY: test", $data);
    }

    public function testAlert(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->alert('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.ALERT: test", $data);
    }

    public function testCritical(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->critical('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.CRITICAL: test", $data);
    }

    public function testError(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->error('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.ERROR: test", $data);
    }

    public function testWarning(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->warning('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.WARNING: test", $data);
    }

    public function testNotice(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->notice('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.NOTICE: test", $data);
    }

    public function testInfo(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->info('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.INFO: test", $data);
    }

    public function testDebug(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->debug('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.DEBUG: test", $data);
    }

    public function testMultipleEntries(): void
    {
        $data = '';
        $logger = $this->mockLogger($data);
        $logger->emergency('test1');
        $logger->alert('test2');
        $logger->critical('test3');
        $logger->error('test4');
        $logger->warning('test5');
        $logger->notice('test6');
        $logger->info('test7');
        $logger->debug('test8');

        $lines = array_filter(explode("\n", $data), fn($line) => !empty($line));
        $this->assertCount(8, $lines);

        $first_line = $lines[0];
        $last_line = $lines[count($lines) - 1];
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.EMERGENCY: test", $first_line);
        $this->assertStringContainsString("[$full_date] FileSystemDriverTest.DEBUG: test", $last_line);
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