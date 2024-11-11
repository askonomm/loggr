<?php

namespace Asko\Loggr\Tests;

use Asko\Loggr\Format;
use Asko\Loggr\Level;
use Asko\Loggr\Message;
use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class FormatTest extends MockeryTestCase
{
    public function testJson(): void
    {
        $message = new Message(
            level: Level::Info,
            trace: debug_backtrace()[0],
            context: ['message' => 'test'],
        );

        $format = Format::JSON;
        $serializedMessage = $format->serialize($message);
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $expectedJson = '{"date":"' . $full_date . '","level":"INFO","context":{"message":"test"},"trace":{"file":"TestCase","line":1182}}';

        $this->assertEquals($expectedJson, $serializedMessage);
    }

    public function testIntelliJ(): void
    {
        $message = new Message(
            level: Level::Info,
            trace: debug_backtrace()[0],
            context: ['message' => 'test'],
        );

        $format = Format::IntelliJ;
        $serializedMessage = $format->serialize($message);
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $expected = "{$full_date} [1182] INFO - TestCase - {\"message\":\"test\"}";

        $this->assertEquals($expected, $serializedMessage);
    }

    public function testIntelliJStr(): void
    {
        $message = new Message(
            level: Level::Info,
            trace: debug_backtrace()[0],
            context: "hello world",
        );

        $format = Format::IntelliJ;
        $serializedMessage = $format->serialize($message);
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $expected = "{$full_date} [1182] INFO - TestCase - hello world";

        $this->assertEquals($expected, $serializedMessage);
    }

    public function testLaravel(): void
    {
        $message = new Message(
            level: Level::Info,
            trace: debug_backtrace()[0],
            context: ['message' => 'test'],
        );

        $format = Format::Laravel;
        $serializedMessage = $format->serialize($message);
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $expected = "[{$full_date}] TestCase.INFO: {\"message\":\"test\"}";

        $this->assertEquals($expected, $serializedMessage);
    }

    public function testLaravelStr(): void
    {
        $message = new Message(
            level: Level::Info,
            trace: debug_backtrace()[0],
            context: "hello world",
        );

        $format = Format::Laravel;
        $serializedMessage = $format->serialize($message);
        $full_date = (new DateTime())->format('Y-m-d H:i:s');
        $expected = "[{$full_date}] TestCase.INFO: hello world";

        $this->assertEquals($expected, $serializedMessage);
    }

    public function testSymfony(): void
    {
        $mock_datetime = Mockery::mock('DateTime');
        $mock_datetime->shouldReceive('format')->andReturn('2020-01-01T00:00:00.000000P');

        $message = new Message(
            level: Level::Info,
            trace: debug_backtrace()[0],
            context: ['message' => 'test'],
        );

        $format = Format::Symfony;
        $serializedMessage = $format->serialize($message, $mock_datetime);
        $full_date = $mock_datetime->format('Y-m-d\TH:i:s.uP');
        $expected = "[$full_date] TestCase.INFO: {\"message\":\"test\"}";

        $this->assertEquals($expected, $serializedMessage);
    }

    public function testSymfonyStr(): void
    {
        $mock_datetime = Mockery::mock('DateTime');
        $mock_datetime->shouldReceive('format')->andReturn('2020-01-01T00:00:00.000000P');

        $message = new Message(
            level: Level::Info,
            trace: debug_backtrace()[0],
            context: "hello world",
        );

        $format = Format::Symfony;
        $serializedMessage = $format->serialize($message, $mock_datetime);
        $full_date = $mock_datetime->format('Y-m-d\TH:i:s.uP');
        $expected = "[$full_date] TestCase.INFO: hello world";

        $this->assertEquals($expected, $serializedMessage);
    }
}