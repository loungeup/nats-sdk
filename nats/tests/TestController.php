<?php

namespace LU\Nats\Tests;

use LU\Resgate\Controller\AbstractNatsController;

class TestController extends AbstractNatsController
{
    public function test()
    {
        return $this->response->result($this->request->getBody()["test"]);
    }
}
