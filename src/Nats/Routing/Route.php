<?php

namespace LU\Nats\Routing;

class Route
{
    # e.g. foo.bar.*
    private string $event;

    # e.g. foo.bar.{id}
    private string $eventRoute;

    private string $namespace;

    private string $controller;

    private string $method;

    private string $name;

    private ?string $description;

    public function __construct(
        string $event,
        string $eventRoute,
        string $namespace,
        string $controller,
        string $method,
        string $name,
        string $description = null
    ) {
        $this->event = $event;
        $this->eventRoute = $eventRoute;
        $this->namespace = $namespace;
        $this->controller = $controller;
        $this->method = $method;
        $this->name = $name;
        $this->description = $description;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function setEvent(string $eventNats)
    {
        $this->event = $eventNats;
    }

    public function getEventRoute(): string
    {
        return $this->eventRoute;
    }

    public function setEventRoute(string $eventRoute)
    {
        $this->eventRoute = $eventRoute;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController(string $controller)
    {
        $this->controller = $controller;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
