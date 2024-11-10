<?php

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
        $this->expectExceptionMessage('No driver has been set.');
        $loggr = new Loggr();
        $loggr->info('test');
    }

    /**
     * @throws \Exception
     */
    public function testNoFormat(): void
    {
        $this->expectExceptionMessage('No format has been set.');
        $loggr = new Loggr(new OutputDriver());
        $loggr->format = null;
        $loggr->info('test');
    }
}