# NATS SDK

Handler for Nats :

- Routing system

## Getting Started

### Installation

Nats Sdk requires PHP >= 7.4.

```shell
composer require nats-sdk/nats
```

### Basic Usage

Create Route file in './routes/\*' folder

```php
<?php
const CONTROLLER_MODEL = ModelController::class;

// $router->add('event.name', [controllerClass, 'methodToCall'], 'Description');

// Example with a CRUD
$router->add(
  "get.service.models",
  [CONTROLLER_MODEL, "getModels"],
  "Get list of models"
);
$router->add(
  "get.service.models.*",
  [CONTROLLER_MODEL, "getModel"],
  "Get a model"
);
$router->add(
  "call.service.models.new",
  [CONTROLLER_MODEL, "insertModel"],
  "Insert a model"
);
$router->add(
  "call.service.models.*.set",
  [CONTROLLER_MODEL, "updateModel"],
  "Update a model"
);
$router->add(
  "call.service.models.*.delete",
  [CONTROLLER_MODEL, "deleteModel"],
  "Delete a model"
);
```

Create an instance of router with routing files

```php
$router = Router::getInstance();
$router->setRoot(strtolower($this->app->get("config")["app"]["name"]));
$router->setNamespace("App\Controllers");

// Get all routes setup files
$files = glob(base_path("routes/") . "*.php", GLOB_BRACE);
foreach ($files as $file) {
  require $file;
}
```

Nats Subscribing with all routes load a message driver to processing with Nats message and return a response.

```php
// Setup Nats handler
$natsHandler = new NatsHandler(
  $config["nats"]["host"],
  $config["nats"]["port"]
);
$natsHandler->setVerbose($config["debug"]);

// Generate routes
$router = Router::getInstance();
$routes = $router->getRoutes();

// Subscribe all nats events
$currentMessageDriver =
  $config["message"]["drivers"][$config["message"]["current"]];
$messageDriver = new $currentMessageDriver();
$natsHandler->subscribeRoutes($routes, $messageDriver);

// Keep waiting events
$natsHandler->wait();
```
