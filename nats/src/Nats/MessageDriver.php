<?php

namespace LU\Nats;

use LU\Nats\Routing\Route;
use Nats\Message;

interface MessageDriver
{
    public function handle(Message $message, Route $route): string;
}
