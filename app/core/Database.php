<?php

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $stmt; // Statement
    private $error;

    public function __construct() {
        // Configurar DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ];

        // Crear una instancia de PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * Prepara la sentencia con la consulta SQL.
     * @param string $sql La consulta SQL.
     */
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Vincula los valores a la sentencia preparada.
     * @param mixed $param El nombre del parámetro.
     * @param mixed $value El valor del parámetro.
     * @param mixed $type El tipo de dato del parámetro.
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Ejecuta la sentencia preparada.
     * @return bool True si la ejecución fue exitosa, false en caso contrario.
     */
    public function execute() {
        return $this->stmt->execute();
    }

    /**
     * Obtiene un conjunto de resultados como un array de objetos.
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Obtiene un único registro como un objeto.
     * @return object
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Obtiene el número de filas afectadas por la última consulta.
     * @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
}