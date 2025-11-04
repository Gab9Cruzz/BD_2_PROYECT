<?php

class Proveedor {
    private $conn;
    private $table = "proveedores";

    public $id;
    public $nombre;
    public $ruc;
    public $telefono;
    public $email;
    public $direccion;
    public $ciudad;
    public $provincia;
    public $pais;

    public function __construct($db) {
        $this->conn = $db;
    }

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
