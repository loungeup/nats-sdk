<?php

namespace LoungeUp\NatsSdk;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use LoungeUp\Nats\Connection;
use LoungeUp\Nats\Options;

class NatsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(Connection::class, function ($app, $params) {
            $url = isset($params["url"]) ? $params["url"] : env("NATS_HOST", "nats") . ":" . env("NATS_PORT", "4222");
            $options = null;

            if (isset($params["options"]) && $params["options"] instanceof Options) {
                $options = $params["options"];
            }

            return Connection::createConnection($url, $options);
        });
    }

    public function provides()
    {
        return [Connection::class];
    }
}
