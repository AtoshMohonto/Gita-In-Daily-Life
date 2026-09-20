<?php
declare(strict_types=1);

final class Router
{
    /** @var array<int,array{0:string,1:string,2:string}> */
    private array $routes = [];

    public function get(string $pattern, string $handler): void
    {
        $this->routes[] = ['GET', $pattern, $handler];
    }

    public function post(string $pattern, string $handler): void
    {
        $this->routes[] = ['POST', $pattern, $handler];
    }

    public function dispatch(): void
    {
        $method = Request::method();
        $uri = $this->currentPath();

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if ($routeMethod !== $method) {
                continue;
            }
            $regex = $this->patternToRegex($pattern);
            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, static fn ($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                $this->callHandler($handler, array_values($params));
                return;
            }
        }

        http_response_code(404);
        (new ErrorController())->notFound();
    }

    private function currentPath(): string
    {
        $uri = Request::uri();
        $base = APP_BASE_PATH;
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . trim($uri, '/');
        return $uri;
    }

    private function patternToRegex(string $pattern): string
    {
        $pattern = rtrim($pattern, '/');
        if ($pattern === '') {
            $pattern = '/';
        }
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#u';
    }

    private function callHandler(string $handler, array $params): void
    {
        [$controllerName, $methodName] = explode('@', $handler);

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo 'Controller not found: ' . e($controllerName);
            return;
        }

        $controller = new $controllerName();
        $controller->$methodName(...$params);
    }
}
