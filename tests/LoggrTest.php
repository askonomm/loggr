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

    public function testIntegerInterpolation(): void
    {
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$full_date}] LoggrTest.INFO: test 1234567890 - {\"integer\":1234567890}");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test {integer}', ['integer' => 1234567890]);
    }

    public function testBooleanInterpolation(): void
    {
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$full_date}] LoggrTest.INFO: test 1 - {\"boolean\":true}");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test {boolean}', ['boolean' => true]);
    }

    public function testDoubleInterpolation(): void
    {
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$full_date}] LoggrTest.INFO: test 1234567890.1235 - {\"double\":1234567890.1234567}");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test {double}', ['double' => 1234567890.1234567]);
    }

    public function testNullInterpolation(): void
    {
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$full_date}] LoggrTest.INFO: test null - {\"null\":null}");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test {null}', ['null' => null]);
    }

    public function testObjectInterpolation(): void
    {
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $this->expectOutputString("[{$full_date}] LoggrTest.INFO: test test - {\"object\":{\"test\":\"test\"}}");
        $loggr = new Loggr(new OutputDriver());
        $loggr->info('test {object.test}', ['object' => (object) ['test' => 'test']]);
    }
}