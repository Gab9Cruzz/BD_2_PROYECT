<?php
session_start();
require_once '../../config/auth.php';
require_once '../../config/conexion.php';
require_once '../../models/Producto.php';
require_once '../../models/Categoria.php';
require_once '../../models/Proveedor.php';

requierePermiso('productos_crear');

$database = new Conexion();
$db = $database->getConnection();

$producto = new Producto($db);
$categoria = new Categoria($db);
$proveedor = new Proveedor($db);

$error = "";

if($_POST) {
    $codigo = 'PROD' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    $producto->codigo = $codigo;
    $producto->nombre = $_POST['nombre'];
    $producto->descripcion = $_POST['descripcion'];
    $producto->marca = $_POST['marca'];
    $producto->precio_compra = $_POST['precio_compra'];
    $producto->precio_venta = $_POST['precio_venta'];
    $producto->iva = $_POST['iva'];
    $producto->stock_actual = $_POST['stock_actual'];
    $producto->stock_minimo = $_POST['stock_minimo'];
    $producto->categoria_id = $_POST['categoria_id'];
    $producto->proveedor_id = !empty($_POST['proveedor_id']) ? $_POST['proveedor_id'] : null;
    
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
    <title>Nuevo Producto - Inventario Tienda</title>
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
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   placeholder="Ej: Camiseta deportiva" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label">Marca *</label>
                            <input type="text" class="form-control" id="marca" name="marca" 
                                   placeholder="Nike, Adidas, HP, etc." required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" 
                                      rows="2" placeholder="Descripción detallada del producto"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="precio_compra" class="form-label">Precio Compra (USD) *</label>
                            <input type="number" class="form-control" id="precio_compra" name="precio_compra" 
                                   step="0.01" min="0" value="0.00" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="precio_venta" class="form-label">Precio Venta (USD) *</label>
                            <input type="number" class="form-control" id="precio_venta" name="precio_venta" 
                                   step="0.01" min="0" value="0.00" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="iva" class="form-label">IVA % *</label>
                            <input type="number" class="form-control" id="iva" name="iva" 
                                   step="0.01" min="0" max="100" value="15.00" required>
                            <small class="text-muted">Ecuador: 15%</small>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="stock_actual" class="form-label">Stock Inicial *</label>
                            <input type="number" class="form-control" id="stock_actual" name="stock_actual" 
                                   min="0" value="0" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" 
                                   min="0" value="5" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccione una categoría...</option>
                                <?php 
                                if($categorias):
                                    while($cat = $categorias->fetch()): 
                                ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                endif;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="proveedor_id" class="form-label">Proveedor</label>
                            <select class="form-select" id="proveedor_id" name="proveedor_id">
                                <option value="">Sin proveedor</option>
                                <?php 
                                if($proveedores):
                                    while($prov = $proveedores->fetch()): 
                                ?>
                                    <option value="<?php echo $prov['id']; ?>">
                                        <?php echo htmlspecialchars($prov['nombre']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Producto
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