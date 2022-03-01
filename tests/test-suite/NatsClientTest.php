<?php

namespace LoungeUp\NatsSdk\Tests;

use LoungeUp\Nats\Connection;
use LoungeUp\NatsSdk\NatsHandler;
use LoungeUp\NatsSdk\Routing\Route;
use PHPUnit\Framework\TestCase;
use LoungeUp\Resgate\ResgateMessageDriver;
use function PHPUnit\Framework\assertEquals;

const NATS_HOST = "127.0.0.1";
const NATS_PORT = 4222;
class NatsClientTest extends TestCase
{
    private Connection $client;

    public function testShouldSubscribeRoute()
    {
        \Co\run(function () {
            $client = Connection::createConnection("nats://" . NATS_HOST . ":" . NATS_PORT);

            $route = new Route(
                "call.testing.testers.test",
                "call.testing.testers.test",
                "LoungeUp\NatsSdk\Tests",
                "TestController",
                "test",
                "testName",
            );
            $natsHandler = new NatsHandler($client);
            $messageDriver = new ResgateMessageDriver();
            $natsHandler->subscribeRoute($route, $messageDriver);

            $response = "";

            $response = $client->request("call.testing.testers.test", '{"test": "it works"}', 5);
            assertEquals('{"result":"it works"}', $response->data);

            $client->close();
        });
    }

    public function testShouldSubscribeRouteWithPathVar()
    {
        \Co\run(function () {
            $client = Connection::createConnection("nats://" . NATS_HOST . ":" . NATS_PORT);

            $route = new Route(
                "call.testing.testers.*.test",
                "call.testing.testers.{test_id}.test",
                "LoungeUp\NatsSdk\Tests",
                "TestController",
                "test",
                "testName",
            );
            $natsHandler = new NatsHandler($client);
            $messageDriver = new ResgateMessageDriver();
            $natsHandler->subscribeRoute($route, $messageDriver);

            $response = "";
            $response = $client->request("call.testing.testers.1.test", '{"test": "it works"}', 5);

            assertEquals('{"result":"it works"}', $response->data);

            $client->close();
        });
    }
}
