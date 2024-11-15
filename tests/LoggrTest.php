<?php

namespace Asko\Loggr\Tests;

use Asko\Loggr\Drivers\OutputDriver;
use Asko\Loggr\Loggr;
use DateTime;
use PHPUnit\Framework\TestCase;

class LoggrTest extends TestCase
{
    public function testNoDriver(): void
    {
        $loggr = new Loggr();
        $loggr->info('test');
        $this->assertEquals("Driver or format not set.", $loggr->error);
    }

    public function testNoFormat(): void
    {
        $loggr = new Loggr(new OutputDriver());
        $loggr->format = null;
        $loggr->info('test');
        $this->assertEquals("Driver or format not set.", $loggr->error);
    }

    public function testInterpolation(): void
    {
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$full_date}] LoggrTest.INFO: test something - {\"interpolation\":\"something\"}");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test {interpolation}', ['interpolation' => 'something']);
    }

    public function testNestedInterpolation(): void
    {
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$full_date}] LoggrTest.INFO: test something - {\"nested\":{\"interpolation\":\"something\"}}");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test {nested.interpolation}', ['nested' => ['interpolation' => 'something']]);
    }
}