<?php

class Controller {

    /**
     * Carga un modelo específico.
     */
    public function model($model) {
        $path = __DIR__ . '/../models/' . $model . '.php';

        if (file_exists($path)) {
            require_once $path;
            return new $model();
        } else {
            die('El modelo no existe: ' . $model);
        }
    }

    /**
     * Carga una vista específica.
     */
    public function view($view, $data = []) {
        extract($data);

        $path = __DIR__ . '/../views/' . $view . '.php';

        if (file_exists($path)) {
            require_once $path;
        } else {
            die('La vista no existe: ' . $view);
        }
    }
}