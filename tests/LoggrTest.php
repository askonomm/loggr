<?php

namespace Asko\Loggr\Tests;

use Asko\Loggr\Drivers\OutputDriver;
use Asko\Loggr\Loggr;
use PHPUnit\Framework\TestCase;

class LoggrTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testNoDriver(): void
    {
        $loggr = new Loggr();
        $loggr->info('test');
        $this->assertEquals("Driver or format not set.", $loggr->error);
    }

    /**
     * @throws \Exception
     */
    public function testNoFormat(): void
    {
        $loggr = new Loggr(new OutputDriver());
        $loggr->format = null;
        $loggr->info('test');
        $this->assertEquals("Driver or format not set.", $loggr->error);
    }
}