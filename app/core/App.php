<?php

class App {

    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 🔹 CONTROLADOR
        if (isset($url[0])) {
            $controllerName = ucwords($url[0]) . 'Controller';

            if (file_exists(__DIR__ . '/../controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // 🔥 AQUÍ ESTABA EL ERROR
        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';

        $this->controller = new $this->controller;

        // 🔹 MÉTODO
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 🔹 PARAMS
        $this->params = $url ? array_values($url) : [];

        // 🔹 EJECUCIÓN
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}