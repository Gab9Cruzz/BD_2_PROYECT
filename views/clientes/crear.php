<?php
session_start();
require_once '../../config/auth.php';
require_once '../../config/conexion.php';
require_once '../../models/Cliente.php';

// Requiere autenticación y permiso para crear clientes
requierePermiso('clientes_crear');

$database = new Conexion();
$db = $database->getConnection();

$cliente = new Cliente($db);

$error = "";

// Procesar formulario
if($_POST) {
    $cliente->tipo_identificacion = $_POST['tipo_identificacion'];
    $cliente->numero_identificacion = $_POST['numero_identificacion'];
    $cliente->nombres = $_POST['nombres'];
    $cliente->apellidos = $_POST['apellidos'];
    $cliente->telefono = $_POST['telefono'];
    $cliente->email = $_POST['email'];
    $cliente->direccion = $_POST['direccion'];
    $cliente->ciudad = $_POST['ciudad'];
    $cliente->provincia = $_POST['provincia'];
    
    if($cliente->crear()) {
        header("Location: ../../index.php?mensaje=" . urlencode("Cliente creado exitosamente"));
        exit();
    } else {
        $error = "Error al crear el cliente.";
    }
}

// Lista de provincias de Ecuador
$provincias = [
    'Azuay', 'Bolívar', 'Cañar', 'Carchi', 'Chimborazo', 'Cotopaxi', 
    'El Oro', 'Esmeraldas', 'Galápagos', 'Guayas', 'Imbabura', 'Loja', 
    'Los Ríos', 'Manabí', 'Morona Santiago', 'Napo', 'Orellana', 'Pastaza', 
    'Pichincha', 'Santa Elena', 'Santo Domingo de los Tsáchilas', 
    'Sucumbíos', 'Tungurahua', 'Zamora Chinchipe'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente - Inventario Tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
                        <div class="col-md-3 mb-3">
                            <label for="tipo_identificacion" class="form-label">Tipo de Identificación *</label>
                            <select class="form-select" id="tipo_identificacion" name="tipo_identificacion" required>
                                <option value="cedula">Cédula</option>
                                <option value="ruc">RUC</option>
                                <option value="pasaporte">Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="numero_identificacion" class="form-label">Número de Identificación *</label>
                            <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" 
                                   placeholder="1234567890" maxlength="13" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nombres" class="form-label">Nombres *</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" 
                                   placeholder="Juan Carlos" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="apellidos" class="form-label">Apellidos *</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                   placeholder="Pérez Gómez" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   placeholder="0987654321">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="cliente@ejemplo.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2" 
                                      placeholder="Calle, número, sector"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="provincia" class="form-label">Provincia</label>
                            <select class="form-select" id="provincia" name="provincia">
                                <option value="">Seleccione...</option>
                                <?php foreach($provincias as $prov): ?>
                                    <option value="<?php echo $prov; ?>"><?php echo $prov; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                   placeholder="Quito, Guayaquil, Cuenca...">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cliente
                        </button>
                        <a href="../../index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>