<?php
/**
 * Archivo de conexión a la base de datos MySQL
 * Utiliza PDO con manejo de errores y prepared statements
 */

class Conexion {
    private $host = "localhost";
    private $db_name = "inventario_tienda";
    private $username = "root";
    private $password = "";
    private $conn;

    /**
     * Obtener conexión a la base de datos
     * @return PDO|null Retorna la conexión PDO o null en caso de error
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            
            // Configurar PDO para que lance excepciones en caso de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configurar para que devuelva arrays asociativos por defecto
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }

        return $this->conn;
    }
}
?>
