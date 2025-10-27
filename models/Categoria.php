<?php
/**
 * Modelo Categoria
 * Gestiona las operaciones de categorías
 */

class Categoria {
    private $conn;
    private $table = "Categoria";

    public $id_categoria;
    public $nombre;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtener todas las categorías
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nombre ASC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
