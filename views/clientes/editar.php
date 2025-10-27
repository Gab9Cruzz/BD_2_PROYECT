<?php
require_once '../../config/conexion.php';
require_once '../../models/Cliente.php';

$database = new Conexion();
$db = $database->getConnection();

$cliente = new Cliente($db);

$mostrarFormulario = false;
$clienteNoEncontrado = false;

if(isset($_GET['id'])) {
    $cliente->id_cliente = $_GET['id'];
    if($cliente->obtenerPorId()) {
        $mostrarFormulario = true;
    } else {
        $clienteNoEncontrado = true;
    }
}

$error = "";
$mensaje = "";

// Capturar mensajes de la URL
if(isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}
if(isset($_GET['error'])) {
    $error = $_GET['error'];
}

if($_POST && isset($_POST['actualizar'])) {
    $cliente->id_cliente = $_POST['id_cliente'];
    $cliente->obtenerPorId(); // Recargar datos
    $cliente->nombre = $_POST['nombre'];
    $cliente->direccion = $_POST['direccion'];
    $cliente->correo = $_POST['correo'];
    
    if($cliente->actualizar()) {
        $mensaje = "Cliente actualizado exitosamente";
    } else {
        $error = "Error al actualizar el cliente.";
    }
}

if($mostrarFormulario) {
    $telefonos = $cliente->obtenerTelefonos();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Inventario</title>
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

        <?php if(isset($mensaje)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(!$mostrarFormulario): ?>
        <!-- Selector de Cliente -->
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
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Correo</th>
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
                                    <td><?php echo $cli['id_cliente']; ?></td>
                                    <td><?php echo htmlspecialchars($cli['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($cli['direccion']); ?></td>
                                    <td><?php echo htmlspecialchars($cli['correo']); ?></td>
                                    <td>
                                        <a href="?id=<?php echo $cli['id_cliente']; ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="id_cliente" value="<?php echo $cliente->id_cliente; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($cliente->nombre); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" 
                                   value="<?php echo htmlspecialchars($cliente->correo); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" 
                                   value="<?php echo htmlspecialchars($cliente->direccion); ?>">
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" name="actualizar" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Gestión de teléfonos -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-telephone"></i> Teléfonos del Cliente</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($telefonos && $telefonos->rowCount() > 0):
                                while($tel = $telefonos->fetch()): 
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tel['telefono']); ?></td>
                                    <td>
                                        <a href="eliminar_telefono.php?id=<?php echo $tel['id_telefono']; ?>&cliente=<?php echo $cliente->id_cliente; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Eliminar este teléfono?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="2" class="text-center">No hay teléfonos registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="agregar_telefono.php" class="mt-3">
                    <input type="hidden" name="id_cliente" value="<?php echo $cliente->id_cliente; ?>">
                    <div class="input-group">
                        <input type="text" class="form-control" name="telefono" 
                               placeholder="Agregar nuevo teléfono" required>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
