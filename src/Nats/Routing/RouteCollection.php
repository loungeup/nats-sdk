<?php

namespace LU\Nats\Routing;

class RouteCollection
{
    protected array $controllerRoutes = [];

    protected array $eventRoutes = [];

    protected array $nameList = [];

    /**
     * Add Route to all arrays
     *
     * @param Route $natsRoute
     */
    public function addToCollections(Route $natsRoute)
    {
        $this->controllerRoutes[$natsRoute->getController()][] = $natsRoute;
        $this->eventRoutes[$natsRoute->getEventRoute()] = $natsRoute;
        $this->nameList[$natsRoute->getName()] = $natsRoute;
    }

    public function getRoutesByController(string $controllerName): ?array
    {
        return $this->controllerRoutes[$controllerName] ?? null;
    }

    public function getRouteByEvent(string $eventName): ?Route
    {
        return $this->eventRoutes[$eventName] ?? null;
    }

    public function getRouteByName(string $name): ?Route
    {
        return $this->nameList[$name] ?? null;
    }

    public function getControllerRoutes(): array
    {
        return $this->controllerRoutes;
    }

    public function setControllerRoutes(array $routes)
    {
        $this->controllerRoutes = $routes;
    }

    public function getEventRoutes(): array
    {
        return $this->eventRoutes;
    }

    public function setEventRoutes(array $eventRoutes)
    {
        $this->eventRoutes = $eventRoutes;
    }

    public function getNameList(): array
    {
        return $this->nameList;
    }

    public function setNameList(array $nameList)
    {
        $this->nameList = $nameList;
    }
}
