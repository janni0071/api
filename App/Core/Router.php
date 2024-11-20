<?php

class Router {
    protected $routes = [];

    public function get($uri, $controller){
        $this->add($uri, $controller, 'GET');
    }
    public function post($uri, $controller){
        $this->add($uri, $controller, 'POST');
    }
    public function put($uri, $controller){
        $this->add($uri, $controller, 'PUT');
    }
    public function patch($uri, $controller){
        $this->add($uri, $controller, 'PATCH');
    }
    public function delete($uri, $controller){
        $this->add($uri, $controller, 'DELETE');
    }
    protected function add($uri, $controller, $method){
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function route($uri, $method){
        foreach ($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                return require base_path($route['controller']);
            }
        }
        $this->abort();
    }
    protected function abort($code = 404, $message = 'Not Found') {
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}