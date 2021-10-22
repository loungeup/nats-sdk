<?php

namespace LU\Nats\Routing;

class Router
{
    private static ?Router $instance = null;

    private ?RouteCollection $routeCollection;

    private string $root;

    private string $controllerNamespace;

    private string $controllerSuffix = "Controller";

    public static function getInstance(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->routeCollection = new RouteCollection();
    }

    /*
     * Extract the name without controller suffix
     */
    public function extractControllerName(string $controller): string
    {
        if (empty($controller)) {
            throw new \Exception("Controller name missing");
        }

        return str_replace($this->controllerSuffix, "", $controller);
    }

    public function formatRouteName(string $event, string $controller): string
    {
        return strtolower($controller) . "_" . $event;
    }

    private function formatEventNats(string $eventRoute): string
    {
        return preg_replace("/({.*?})/", "*", $eventRoute);
    }

    public function checkActionCallExist(string $controller, string $method): bool
    {
        return class_exists($this->controllerNamespace . "\\" . $controller) &&
            method_exists($this->controllerNamespace . "\\" . $controller, $method);
    }

    public function add(string $eventRoute, array $actionCall, string $description = null)
    {
        [$controllerComplete, $method] = $actionCall;
        if ($this->checkActionCallExist($controllerComplete, $method)) {
            $controller = $this->extractControllerName($controllerComplete);
            $name = $this->formatRouteName($eventRoute, $controller);
            $eventNats = $this->formatEventNats($eventRoute);
            // Add new route
            $currentRoute = new Route(
                $eventNats,
                $eventRoute,
                $this->controllerNamespace,
                $controllerComplete,
                $method,
                $name,
                $description
            );
            $this->routeCollection->addToCollections($currentRoute);
        }
    }

    public function getRoutes(): array
    {
        return $this->routeCollection->getEventRoutes();
    }

    public function getRoutesByService($name): ?Route
    {
        return $this->routeCollection->getRoutesByController($name);
    }

    /**
     * Print to stdout all Route collection beautifier
     */
    public function printRouter()
    {
        $separator = "-";
        $rows = "%-60s| %-50s| %-70s| %-40s \n";
        $tableWidth = 200;

        printf("\e[32m");
        for ($i = 0; $i < $tableWidth; $i++) {
            printf($separator);
        }

        printf("\n");

        printf($rows, "Name", "Event", "Action", "Description");

        for ($i = 0; $i < $tableWidth; $i++) {
            printf($separator);
        }

        printf("\e[0m");
        printf("\n");

        $routes = $this->getRoutes();
        foreach ($routes as $route) {
            printf(
                $rows,
                $route->getName(),
                $route->getEventRoute(),
                $route->getNamespace() . "\\" . $route->getController() . "@\033[1m" . $route->getMethod() . "\e[0m",
                $route->getDescription()
            );
        }
    }

    public function getRouteCollection(): ?RouteCollection
    {
        return $this->routeCollection;
    }

    public function setRouteCollection(?RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    public function setRoot(string $root)
    {
        $this->root = $root;
    }

    public function getControllerNamespace(): string
    {
        return $this->controllerNamespace;
    }

    public function setControllerNamespace(string $controllerNamespace)
    {
        $this->controllerNamespace = $controllerNamespace;
    }

    public function getControllerSuffix(): string
    {
        return $this->controllerSuffix;
    }

    public function setControllerSuffix(string $controllerSuffix)
    {
        $this->controllerSuffix = $controllerSuffix;
    }
}
