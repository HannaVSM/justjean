<?php

class Controller {

    /**
     * Carga un modelo específico.
     * @param string $model El nombre del modelo a cargar (ej: 'Usuario').
     * @return object Una instancia del modelo solicitado.
     */
    public function model($model) {
        // Requiere el archivo del modelo desde la carpeta de modelos
        require_once 'app/models/' . $model . '.php';
        // Retorna una nueva instancia de la clase del modelo
        return new $model();
    }

    /**
     * Carga una vista específica.
     * @param string $view El nombre de la vista a cargar (ej: 'auth/login').
     * @param array $data Los datos a pasar a la vista.
     */
    public function view($view, $data = []) {
        // Extrae los datos del array para que puedan ser usados como variables en la vista
        extract($data);
        
        // Verifica si el archivo de la vista existe
        if (file_exists('app/views/' . $view . '.php')) {
            // Requiere el archivo de la vista
            require_once 'app/views/' . $view . '.php';
        } else {
            // Si la vista no existe, muestra un error
            die('La vista no existe.');
        }
    }
}