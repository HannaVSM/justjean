<?php

class Database {

    private $dbh; // Database Handler
    private $stmt; // Statement
    private $error;

    public function __construct() {

        $host   = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
        $user   = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root';
        $pass   = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';
        $dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: '';
        $port   = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->dbh = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log("Error DB: " . $e->getMessage());
            die("Ocurrió un error al conectar con la base de datos. Intenta más tarde.");
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