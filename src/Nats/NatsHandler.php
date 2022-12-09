<?php

namespace LoungeUp\NatsSdk;

use Illuminate\Support\Facades\Log;
use LoungeUp\Nats\Connection;
use LoungeUp\Nats\Message;
use LoungeUp\NatsSdk\Routing\Route;

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
                        $message->subject .
                        " | PAYLOAD: " .
                        $message->data,
                );
            }

            $output = $messageDriver->handle($message, $route);

            if ($this->verbose) {
                Log::debug("REPLY : " . $output);
            }

            if ($message->reply && is_string($output)) {
                $message->respond($output);
            }
        });

        if ($this->verbose) {
            Log::debug("Subscribe: " . $route->getEventRoute());
        }
    }

    public function getClient(): Connection
    {
        return $this->client;
    }

    public function setVerbose(bool $verbose)
    {
        $this->verbose = $verbose;
    }
}
