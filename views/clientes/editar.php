<?php
session_start();
require_once '../../config/auth.php';
require_once '../../config/conexion.php';
require_once '../../models/Cliente.php';

requierePermiso('clientes_editar');

$database = new Conexion();
$db = $database->getConnection();

$cliente = new Cliente($db);

$mostrarFormulario = false;
$clienteNoEncontrado = false;
$error = "";

if(isset($_GET['id'])) {
    $cliente->id = $_GET['id'];
    if($cliente->obtenerPorId()) {
        $mostrarFormulario = true;
    } else {
        $clienteNoEncontrado = true;
    }
}

if(isset($_GET['eliminar']) && isset($_GET['id'])) {
    $cliente->id = $_GET['id'];
    if($cliente->eliminar()) {
        header("Location: ../../index.php?mensaje=" . urlencode("Cliente eliminado exitosamente"));
        exit();
    } else {
        $error = "Error al eliminar el cliente. Puede tener ventas asociadas.";
    }
}

if($_POST) {
    $cliente->id = $_POST['id'];
    $cliente->tipo_identificacion = $_POST['tipo_identificacion'];
    $cliente->numero_identificacion = $_POST['numero_identificacion'];
    $cliente->nombres = $_POST['nombres'];
    $cliente->apellidos = $_POST['apellidos'];
    $cliente->telefono = $_POST['telefono'];
    $cliente->email = $_POST['email'];
    $cliente->direccion = $_POST['direccion'];
    $cliente->ciudad = $_POST['ciudad'];
    $cliente->provincia = $_POST['provincia'];
    
    if($cliente->actualizar()) {
        header("Location: ../../index.php?mensaje=" . urlencode("Cliente actualizado exitosamente"));
        exit();
    } else {
        $error = "Error al actualizar el cliente.";
    }
}

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
    <title>Editar Cliente - Inventario Tienda</title>
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
                <h2><i class="bi bi-pencil"></i> Editar Cliente</h2>
            </div>
            <div class="col text-end">
                <a href="../../index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <?php if($clienteNoEncontrado): ?>
            <div class="alert alert-danger">
                Cliente no encontrado. Verifique el ID e intente nuevamente.
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(!$mostrarFormulario): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5>Seleccione el Cliente a Editar</h5>
                <form method="GET" action="">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="id" class="form-label">ID del Cliente</label>
                            <input type="number" class="form-control" id="id" name="id" 
                                   placeholder="Ingrese el ID del cliente" min="1" required>
                            <small class="text-muted">O seleccione de la lista:</small>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <h6>Lista de Clientes Disponibles:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Identificación</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $todosClientes = $cliente->obtenerTodos();
                            if($todosClientes && $todosClientes->rowCount() > 0):
                                while($cli = $todosClientes->fetch()): 
                            ?>
                                <tr>
                                    <td><?php echo $cli['id']; ?></td>
                                    <td><?php echo htmlspecialchars($cli['numero_identificacion']); ?></td>
                                    <td><?php echo htmlspecialchars($cli['nombres']); ?></td>
                                    <td><?php echo htmlspecialchars($cli['apellidos']); ?></td>
                                    <td><?php echo htmlspecialchars($cli['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($cli['email']); ?></td>
                                    <td>
                                        <a href="?id=<?php echo $cli['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay clientes registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $cliente->id; ?>">
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="tipo_identificacion" class="form-label">Tipo de Identificación *</label>
                            <select class="form-select" id="tipo_identificacion" name="tipo_identificacion" required>
                                <option value="cedula" <?php echo ($cliente->tipo_identificacion == 'cedula') ? 'selected' : ''; ?>>Cédula</option>
                                <option value="ruc" <?php echo ($cliente->tipo_identificacion == 'ruc') ? 'selected' : ''; ?>>RUC</option>
                                <option value="pasaporte" <?php echo ($cliente->tipo_identificacion == 'pasaporte') ? 'selected' : ''; ?>>Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="numero_identificacion" class="form-label">Número de Identificación *</label>
                            <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" 
                                   value="<?php echo htmlspecialchars($cliente->numero_identificacion); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nombres" class="form-label">Nombres *</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" 
                                   value="<?php echo htmlspecialchars($cliente->nombres); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="apellidos" class="form-label">Apellidos *</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                   value="<?php echo htmlspecialchars($cliente->apellidos); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="<?php echo htmlspecialchars($cliente->telefono); ?>">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($cliente->email); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"><?php echo htmlspecialchars($cliente->direccion); ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="provincia" class="form-label">Provincia</label>
                            <select class="form-select" id="provincia" name="provincia">
                                <option value="">Seleccione...</option>
                                <?php foreach($provincias as $prov): ?>
                                    <option value="<?php echo $prov; ?>" <?php echo ($cliente->provincia == $prov) ? 'selected' : ''; ?>>
                                        <?php echo $prov; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                   value="<?php echo htmlspecialchars($cliente->ciudad); ?>">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Cliente
                        </button>
                        <a href="../../index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <a href="?eliminar=1&id=<?php echo $cliente->id; ?>" 
                           class="btn btn-danger float-end" 
                           onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>