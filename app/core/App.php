<?php

class App {

    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Determina el controlador a cargar
        // Verifica si el archivo del controlador existe en la carpeta controllers
        if (isset($url[0]) && file_exists('app/controllers/' . ucwords($url[0]) . 'Controller.php')) {
            $this->controller = ucwords($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Determina el método a llamar
        // Verifica si el método existe en el controlador
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Obtiene los parámetros de la URL
        $this->params = $url ? array_values($url) : [];

        // Llama al método del controlador con los parámetros correspondientes
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parsea la URL obtenida del parámetro 'url'
     * @return array La URL descompuesta en un array
     */
    public function parseUrl() {
        if (isset($_GET['url'])) {
            // rtrim para quitar la barra final, sanitizar y explotar la URL
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}