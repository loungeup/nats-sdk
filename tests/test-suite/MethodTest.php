<?php

namespace LoungeUp\NatsSdk\Tests;

use LoungeUp\NatsSdk\Routing\Route;
use LoungeUp\NatsSdk\Routing\Router;
use LoungeUp\Resgate\Message\Request;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;

class MethodTest extends TestCase
{
    public function testShouldAddRoutes()
    {
        $router = Router::getInstance();
        $router->setControllerNamespace("LoungeUp\NatsSdk\Tests");
        $router->setControllerSuffix("Controller");
        require __DIR__ . "/../routes/testing.php";
        $routes = $router->getRoutes();
        assertArrayHasKey("get.testing.testers.test", $routes);
        assertArrayHasKey("get.testing.testers.{test_id}.test", $routes);
        assertArrayHasKey("call.testing.testers.test", $routes);
    }

    public function testShouldParseSubject()
    {
        $route = new Route(
            "call.testing.testers.*.test",
            "call.testing.testers.{test_id}.test",
            "LoungeUp\NatsSdk\Tests",
            "TestController",
            "test",
            "testName",
        );
        $route2 = new Route(
            "call.testing.testers.test.*",
            "call.testing.testers.test.{test_id}",
            "LoungeUp\NatsSdk\Tests",
            "TestController",
            "test",
            "testName",
        );
        $route3 = new Route(
            "call.testing.testers.*.*.test",
            "call.testing.testers.{test_id}.{test_number}.test",
            "LoungeUp\NatsSdk\Tests",
            "TestController",
            "test",
            "testName",
        );
        $request = new Request($route, "call.testing.testers.12345.test", "");
        $request2 = new Request($route2, "call.testing.testers.test.12345", "");
        $request3 = new Request($route3, "call.testing.testers.12345.54321.test", "");

        assertEquals(["test_id" => "12345"], $request->getEventParams());
        assertEquals(["test_id" => "12345"], $request2->getEventParams());
        assertEquals(["test_id" => "12345", "test_number" => "54321"], $request3->getEventParams());
    }
}
