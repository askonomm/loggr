<?php

namespace Asko\Loggr\Tests;

use DateTime;
use Asko\Loggr\Drivers\FileSystemDriver;
use Asko\Loggr\Loggr;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class FileSystemDriverTest extends MockeryTestCase
{
    /**
     * @throws \Exception
     */
    public function testEmergency(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->emergency('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.EMERGENCY: test", $data);
    }

    /**
     * @throws \Exception
     */
    public function testAlert(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->alert('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.ALERT: test", $data);
    }

    /**
     * @throws \Exception
     */
    public function testCritical(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->critical('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.CRITICAL: test", $data);
    }

    /**
     * @throws \Exception
     */
    public function testError(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->error('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.ERROR: test", $data);
    }

    /**
     * @throws \Exception
     */
    public function testWarning(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->warning('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.WARNING: test", $data);
    }

    /**
     * @throws \Exception
     */
    public function testNotice(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->notice('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.NOTICE: test", $data);
    }

    /**
     * @throws \Exception
     */
    public function testInfo(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->info('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.INFO: test", $data);
    }

    /**
     * @throws \Exception
     */
    public function testDebug(): void
    {
        $data = '';
        $loggr = $this->mockLogger($data);
        $loggr->debug('test');
        $full_date = (new DateTime)->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.DEBUG: test", $data);
    }

    /**
     * @throws \Exception
     */
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
        $last_line = end($lines);
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.EMERGENCY: test", $first_line);
        $this->assertStringContainsString("[{$full_date}] FileSystemDriverTest.DEBUG: test", $last_line);
    }

    public function testNoPath(): void
    {
        $loggr = new Loggr(new FileSystemDriver('path-does-not-exist'));
        $loggr->emergency('test');
        $sep = DIRECTORY_SEPARATOR;
        $date = (new DateTime())->format('Y-m-d');
        $this->assertEquals("Log file could not be created at path: path-does-not-exist{$sep}{$date}.log", $loggr->error);
    }

    public function testCantWrite(): void
    {
        $driver = Mockery::mock(FileSystemDriver::class, [''])->makePartial();
        $driver->shouldReceive('log')->passthru();
        $driver->shouldReceive('exists')->andReturn(true);
        $driver->shouldReceive('isWriteable')->andReturns(false);
        $driver->shouldReceive('write')->never();
        $loggr = new Loggr($driver);
        $loggr->emergency('test');
        $sep = DIRECTORY_SEPARATOR;
        $date = (new DateTime())->format('Y-m-d');
        $this->assertEquals("Log file is not writeable at path: {$sep}{$date}.log", $loggr->error);
    }

    private function mockLogger(string &$result): Loggr
    {
        $driver = Mockery::mock(FileSystemDriver::class, [''])->makePartial();
        $driver->shouldReceive('log')->passthru();
        $driver->shouldReceive('exists')->andReturn(true);
        $driver->shouldReceive('isWriteable')->andReturns(true);
        $driver->shouldReceive('write')->withArgs(function ($path, $data) use (&$result) {
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