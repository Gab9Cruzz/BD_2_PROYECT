<?php
require_once '../../config/conexion.php';
require_once '../../models/Cliente.php';

if($_POST && isset($_POST['id_cliente']) && isset($_POST['telefono'])) {
    $database = new Conexion();
    $db = $database->getConnection();
    
    $cliente = new Cliente($db);
    $cliente->id_cliente = $_POST['id_cliente'];
    
    $telefono = trim($_POST['telefono']);
    
    if(!empty($telefono)) {
        if($cliente->agregarTelefono($telefono)) {
            header("Location: editar.php?id=" . $_POST['id_cliente'] . "&mensaje=" . urlencode("Teléfono agregado exitosamente"));
            exit();
        } else {
            header("Location: editar.php?id=" . $_POST['id_cliente'] . "&error=" . urlencode("Error al agregar el teléfono"));
            exit();
        }
    } else {
        header("Location: editar.php?id=" . $_POST['id_cliente'] . "&error=" . urlencode("El teléfono no puede estar vacío"));
        exit();
    }
} else {
    header("Location: editar.php");
    exit();
}
?>
