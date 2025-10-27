<?php
require_once '../../config/conexion.php';

if(isset($_GET['id']) && isset($_GET['cliente'])) {
    $database = new Conexion();
    $db = $database->getConnection();
    
    $id_telefono = $_GET['id'];
    $id_cliente = $_GET['cliente'];
    
    // Eliminar directamente con SQL
    $query = "DELETE FROM Telefono_Cliente WHERE id_telefono = :id_telefono";
    
    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id_telefono", $id_telefono);
        
        if($stmt->execute()) {
            header("Location: editar.php?id=" . $id_cliente . "&mensaje=" . urlencode("Teléfono eliminado exitosamente"));
            exit();
        } else {
            header("Location: editar.php?id=" . $id_cliente . "&error=" . urlencode("Error al eliminar el teléfono"));
            exit();
        }
    } catch(PDOException $e) {
        header("Location: editar.php?id=" . $id_cliente . "&error=" . urlencode("Error: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: editar.php");
    exit();
}
?>
