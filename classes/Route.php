<?php

namespace Router;

class Route {
    private string $request_url;
    private string $request_method;
    private array $middleware = [];
    private array $before_filters = [];
    private array $after_filters = [];
    private array $params = [];
    private array $routes = [];
    private array $named_routes = [];
    private array $error_page = [];

    function __construct() {
        $this->request_method = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->request_url = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
        
        if (empty($this->request_method) || empty($this->request_url)) {
            trigger_error("Failed to initialize request method or URL", E_USER_ERROR);
        }
    }

    private function addRoute(string $pattern, callable $callback, string $method, ?string $name = null, bool $is_match = false) {
        if (empty($pattern) || !is_callable($callback) || empty($method)) {
            trigger_error("Invalid parameters for addRoute method", E_USER_WARNING);
            return;
        }
        
        $route = [
            'pattern' => $pattern,
            'callback' => $callback,
            'method' => strtoupper($method),
            'is_match' => $is_match
        ];
        
        if ($name) {
            if (isset($this->named_routes[$name])) {
                trigger_error("Route name {$name} already exists", E_USER_WARNING);
            }
            $this->named_routes[$name] = $route;
        }
        
        $this->routes[] = $route;
    }

    final public function for(string $request_url, callable $callback, string $method = 'GET', ?string $name = null) {
        if (empty($request_url) || !is_callable($callback)) {
            trigger_error("Invalid parameters for 'for' method", E_USER_WARNING);
            return;
        }
        $this->addRoute($request_url, $callback, $method, $name);
    }

    final public function match(string $url_matching_pattern, callable $callback, string $method = 'GET', ?string $name = null) {
        if (empty($url_matching_pattern) || !is_callable($callback)) {
            trigger_error("Invalid parameters for 'match' method", E_USER_WARNING);
            return;
        }
        $this->addRoute($url_matching_pattern, $callback, $method, $name, true);
    }

    final public function error_page(callable $callback) {
        if (!is_callable($callback)) {
            trigger_error("Invalid callback for error_page", E_USER_WARNING);
            return;
        }
        $this->error_page['404'] = $callback;
    }

    final public function useMiddleware(callable $middleware) {
        if (!is_callable($middleware)) {
            trigger_error("Invalid middleware", E_USER_WARNING);
            return;
        }
        $this->middleware[] = $middleware;
    }

    final public function addFilter(string $pattern, callable $filter, string $type = 'before') {
        if (empty($pattern) || !is_callable($filter)) {
            trigger_error("Invalid parameters for addFilter method", E_USER_WARNING);
            return;
        }
        
        $filterArray = ['pattern' => $pattern, 'filter' => $filter];
        
        if ($type === 'after') {
            $this->after_filters[] = $filterArray;
        } else {
            $this->before_filters[] = $filterArray;
        }
    }

    final public function run() {
        if (empty($this->routes)) {
            trigger_error("No routes defined", E_USER_WARNING);
            return;
        }

        foreach ($this->routes as $route) {
            if ($this->request_method === strtoupper($route['method']) && $this->matchPattern($route['pattern'], $this->request_url, $this->params)) {
                $this->runFilters($this->before_filters);
                $this->runMiddleware();
                call_user_func($route['callback'], $this->params);
                $this->runFilters($this->after_filters);
                return; // Stop after the first matching route
            }
        }
        
        if (isset($this->error_page['404'])) {
            call_user_func($this->error_page['404']);
        } else {
            trigger_error("404 Not Found and no error page defined", E_USER_ERROR);
        }
    }

    private function runMiddleware() {
        foreach ($this->middleware as $middleware) {
            if (!is_callable($middleware)) {
                trigger_error("Invalid middleware in runMiddleware", E_USER_WARNING);
                continue;
            }
            call_user_func($middleware);
        }
    }

    private function runFilters(array $filters) {
        foreach ($filters as $filter) {
            $pattern = "#^" . str_replace('*', '.*', $filter['pattern']) . "$#";
            if (preg_match($pattern, $this->request_url)) {
                if (!is_callable($filter['filter'])) {
                    trigger_error("Invalid filter in runFilters", E_USER_WARNING);
                    continue;
                }
                call_user_func($filter['filter']);
            }
        }
    }

    private function matchPattern(string $pattern, string $url, array &$params): bool {
        $pattern = preg_replace('/{([^}]+)}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = "#^" . $pattern . "$#";
        if (preg_match($pattern, $url, $matches)) {
            $params = $matches;
            return true;
        }
        return false;
    }

    final public function generateUrl(string $name, array $params = []): string {
        if (!isset($this->named_routes[$name])) {
            trigger_error("No route named {$name}", E_USER_ERROR);
        }
        
        $route = $this->named_routes[$name];
        $url = $route['pattern'];
        
        foreach ($params as $key => $value) {
            if (strpos($url, "{{$key}}") === false) {
                trigger_error("Parameter {$key} not found in route pattern", E_USER_WARNING);
                continue;
            }
            $url = str_replace("{{$key}}", $value, $url);
        }
        
        return $url;
    }

    // Custom method to safely access array elements
    private function getArrayElement(array $array, $key, $default = null) {
        if (!array_key_exists($key, $array)) {
            trigger_error("Undefined array key '{$key}'", E_USER_WARNING);
            return $default;
        }
        return $array[$key];
    }
}
