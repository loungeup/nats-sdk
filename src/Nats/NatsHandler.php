<?php

namespace LU\Nats;

use Illuminate\Support\Facades\Log;
use LU\Nats\Routing\Route;
use Nats\Connection;
use Nats\Message;

class NatsHandler
{
    private Connection $client;

    private bool $verbose;

    public function __construct(Connection $connection, bool $verbose = false)
    {
        $this->client = $connection;
        $this->verbose = $verbose;
    }

    public function subscribeRoutes(array $routes, MessageDriver $messageDriver)
    {
        foreach ($routes as $route) {
            $this->subscribeRoute($route, $messageDriver);
        }
    }

    public function subscribeRoute(Route $route, MessageDriver $messageDriver)
    {
        $this->client->subscribe($route->getEvent(), function (Message $message) use ($route, $messageDriver) {
            if ($this->verbose) {
                Log::debug(
                    "HIT: " .
                        $route->getEventRoute() .
                        " | SUBJECT: " .
                        $message->getSubject() .
                        " | PAYLOAD: " .
                        $message->getBody()
                );
            }

            $output = $messageDriver->handle($message, $route);

            if ($this->verbose) {
                Log::debug("REPLY : " . $output);
            }

            $message->reply($output);
        });

        if ($this->verbose) {
            Log::debug("Subscribe: " . $route->getEventRoute());
        }
    }

    public function getClient(): Connection
    {
        return $this->client;
    }

    public function wait()
    {
        $this->client->wait();
    }

    public function setVerbose(bool $verbose)
    {
        $this->verbose = $verbose;
    }
}
