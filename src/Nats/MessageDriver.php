<?php

namespace LoungeUp\NatsSdk;

use LoungeUp\NatsSdk\Routing\Route;
use LoungeUp\Nats\Message;

interface MessageDriver
{
    public function handle(Message $message, Route $route);
}
