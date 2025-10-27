<?php
require_once '../../config/conexion.php';
require_once '../../models/Cliente.php';

$database = new Conexion();
$db = $database->getConnection();

$cliente = new Cliente($db);

$mensaje = "";
$error = "";

if($_POST) {
    $cliente->nombre = $_POST['nombre'];
    $cliente->direccion = $_POST['direccion'];
    $cliente->correo = $_POST['correo'];
    
    if($cliente->crear()) {
        // Agregar teléfonos
        if(!empty($_POST['telefonos'])) {
            $telefonos = explode(',', $_POST['telefonos']);
            foreach($telefonos as $tel) {
                $tel = trim($tel);
                if(!empty($tel)) {
                    $cliente->agregarTelefono($tel);
                }
            }
        }
        
        header("Location: ../../index.php?mensaje=" . urlencode("Cliente creado exitosamente"));
        exit();
    } else {
        $error = "Error al crear el cliente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente - Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">
                <i class="bi bi-shop"></i> Inventario Tienda
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col">
                <h2><i class="bi bi-person-plus"></i> Nuevo Cliente</h2>
            </div>
            <div class="col text-end">
                <a href="../../index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" 
                                   placeholder="cliente@email.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" 
                                   placeholder="Calle, número, ciudad">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefonos" class="form-label">Teléfonos</label>
                            <input type="text" class="form-control" id="telefonos" name="telefonos" 
                                   placeholder="Separe múltiples teléfonos con comas">
                            <small class="text-muted">Ejemplo: 999-111-222, 999-111-223</small>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
