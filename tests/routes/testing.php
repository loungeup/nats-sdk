<?php

$router->add("get.testing.testers.test", [TestController::class, "test"], "test");

$router->add("get.testing.testers.{test_id}.test", [TestController::class, "test"], "test");

$router->add("call.testing.testers.test", [TestController::class, "test"], "test");
