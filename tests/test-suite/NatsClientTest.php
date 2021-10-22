<?php

namespace LU\Nats\Tests;

use Nats\Connection;
use LU\Nats\NatsHandler;
use LU\Nats\Routing\Route;
use PHPUnit\Framework\TestCase;
use LU\Resgate\ResgateMessageDriver;
use function PHPUnit\Framework\assertEquals;

const NATS_HOST = "nats";
const NATS_PORT = 4222;
class NatsClientTest extends TestCase
{
    private Connection $client;

    public function setUp(): void
    {
        $connectionOptions = new \Nats\ConnectionOptions();
        $connectionOptions->setHost(NATS_HOST)->setPort(NATS_PORT);
        $client = new \Nats\Connection($connectionOptions);
        $client->connect(-1);
        $this->client = $client;
    }

    public function tearDown(): void
    {
        $this->client->close();
    }

    public function testShouldSubscribeRoute()
    {
        $route = new Route(
            "call.testing.testers.test",
            "call.testing.testers.test",
            "LU\Nats\Tests",
            "TestController",
            "test",
            "testName"
        );
        $natsHandler = new NatsHandler($this->client);
        $messageDriver = new ResgateMessageDriver();
        $natsHandler->subscribeRoute($route, $messageDriver);

        $response = "";
        $this->client->request("call.testing.testers.test", '{"test": "it works"}', function ($message) use (
            &$response
        ) {
            $response = $message->getBody();
        });
        $this->client->wait(1);
        assertEquals('{"result":"it works"}', $response);
    }

    public function testShouldSubscribeRouteWithPathVar()
    {
        $route = new Route(
            "call.testing.testers.*.test",
            "call.testing.testers.{test_id}.test",
            "LU\Nats\Tests",
            "TestController",
            "test",
            "testName"
        );
        $natsHandler = new NatsHandler($this->client);
        $messageDriver = new ResgateMessageDriver();
        $natsHandler->subscribeRoute($route, $messageDriver);

        $response = "";
        $this->client->request("call.testing.testers.1.test", '{"test": "it works"}', function ($message) use (
            &$response
        ) {
            $response = $message->getBody();
        });
        $this->client->wait(1);
        assertEquals('{"result":"it works"}', $response);
    }
}
