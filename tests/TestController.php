<?php

namespace LoungeUp\NatsSdk\Tests;

use LoungeUp\Resgate\Controller\AbstractNatsController;

class TestController extends AbstractNatsController
{
    public function test()
    {
        return $this->response->result($this->request->getBody()["test"]);
    }
}
