<?php
require_once '../../config/conexion.php';
require_once '../../models/Producto.php';
require_once '../../models/Categoria.php';
require_once '../../models/Proveedor.php';

$database = new Conexion();
$db = $database->getConnection();

$producto = new Producto($db);
$categoria = new Categoria($db);
$proveedor = new Proveedor($db);

$mensaje = "";
$error = "";

if($_POST) {
    $producto->nombre = $_POST['nombre'];
    $producto->talla = $_POST['talla'];
    $producto->color = $_POST['color'];
    $producto->precio = $_POST['precio'];
    $producto->stock = $_POST['stock'];
    $producto->stock_minimo = $_POST['stock_minimo'];
    $producto->id_categoria = $_POST['id_categoria'];
    $producto->id_proveedor = !empty($_POST['id_proveedor']) ? $_POST['id_proveedor'] : null;
    
    if($producto->crear()) {
        header("Location: ../../index.php?mensaje=" . urlencode("Producto creado exitosamente"));
        exit();
    } else {
        $error = "Error al crear el producto.";
    }
}

$categorias = $categoria->obtenerTodos();
$proveedores = $proveedor->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto - Inventario</title>
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
                <h2><i class="bi bi-plus-circle"></i> Nuevo Producto</h2>
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
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="talla" class="form-label">Talla</label>
                            <input type="text" class="form-control" id="talla" name="talla" placeholder="S, M, L, XL, etc.">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="color" name="color" placeholder="Rojo, Azul, etc.">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="precio" class="form-label">Precio *</label>
                            <input type="number" class="form-control" id="precio" name="precio" 
                                   step="0.01" min="0" value="0.00" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="stock" class="form-label">Stock Inicial *</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" min="0" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="id_categoria" class="form-label">Categoría *</label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione...</option>
                                <?php 
                                if($categorias):
                                    while($cat = $categorias->fetch()): 
                                ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>">
                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="id_proveedor" class="form-label">Proveedor</label>
                            <select class="form-select" id="id_proveedor" name="id_proveedor">
                                <option value="">Sin proveedor</option>
                                <?php 
                                if($proveedores):
                                    while($prov = $proveedores->fetch()): 
                                ?>
                                    <option value="<?php echo $prov['id_proveedor']; ?>">
                                        <?php echo htmlspecialchars($prov['nombre']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
