<?php
session_start();
require_once '../../config/auth.php';
require_once '../../config/conexion.php';
require_once '../../models/Producto.php';
require_once '../../models/Categoria.php';
require_once '../../models/Proveedor.php';

requierePermiso('productos_editar');

$database = new Conexion();
$db = $database->getConnection();

$producto = new Producto($db);
$categoria = new Categoria($db);
$proveedor = new Proveedor($db);

$mostrarFormulario = false;
$productoNoEncontrado = false;
$error = "";

if(isset($_GET['id'])) {
    $producto->id = $_GET['id'];
    if($producto->obtenerPorId()) {
        $mostrarFormulario = true;
    } else {
        $productoNoEncontrado = true;
    }
}

if(isset($_GET['eliminar']) && isset($_GET['id'])) {
    $producto->id = $_GET['id'];
    if($producto->eliminar()) {
        header("Location: ../../index.php?mensaje=" . urlencode("Producto eliminado exitosamente"));
        exit();
    } else {
        $error = "Error al eliminar el producto. Puede tener ventas asociadas.";
    }
}

if($_POST) {
    $producto->id = $_POST['id'];
    $producto->codigo = $_POST['codigo'];
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
    
    if($producto->actualizar()) {
        header("Location: ../../index.php?mensaje=" . urlencode("Producto actualizado exitosamente"));
        exit();
    } else {
        $error = "Error al actualizar el producto.";
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
    <title>Editar Producto - Inventario Tienda</title>
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
                <h2><i class="bi bi-pencil"></i> Editar Producto</h2>
            </div>
            <div class="col text-end">
                <a href="../../index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <?php if($productoNoEncontrado): ?>
            <div class="alert alert-danger">
                Producto no encontrado. Verifique el ID e intente nuevamente.
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
                <h5>Seleccione el Producto a Editar</h5>
                <form method="GET" action="">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="id" class="form-label">ID del Producto</label>
                            <input type="number" class="form-control" id="id" name="id" 
                                   placeholder="Ingrese el ID del producto" min="1" required>
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

                <h6>Lista de Productos Disponibles:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $todosProductos = $producto->obtenerTodos();
                            if($todosProductos && $todosProductos->rowCount() > 0):
                                while($prod = $todosProductos->fetch()): 
                            ?>
                                <tr>
                                    <td><?php echo $prod['id']; ?></td>
                                    <td><?php echo htmlspecialchars($prod['codigo']); ?></td>
                                    <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($prod['marca']); ?></td>
                                    <td><?php echo htmlspecialchars($prod['categoria_nombre']); ?></td>
                                    <td><?php echo $prod['stock_actual']; ?></td>
                                    <td>
                                        <a href="?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay productos registrados</td>
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
                    <input type="hidden" name="id" value="<?php echo $producto->id; ?>">
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo"
                                   value="<?php echo htmlspecialchars($producto->codigo); ?>" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($producto->nombre); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="marca" class="form-label">Marca *</label>
                            <input type="text" class="form-control" id="marca" name="marca" 
                                   value="<?php echo htmlspecialchars($producto->marca); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2"><?php echo htmlspecialchars($producto->descripcion); ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="precio_compra" class="form-label">Precio Compra (USD) *</label>
                            <input type="number" class="form-control" id="precio_compra" name="precio_compra" 
                                   step="0.01" min="0" value="<?php echo $producto->precio_compra; ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="precio_venta" class="form-label">Precio Venta (USD) *</label>
                            <input type="number" class="form-control" id="precio_venta" name="precio_venta" 
                                   step="0.01" min="0" value="<?php echo $producto->precio_venta; ?>" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="iva" class="form-label">IVA % *</label>
                            <input type="number" class="form-control" id="iva" name="iva" 
                                   step="0.01" min="0" max="100" value="<?php echo $producto->iva; ?>" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="stock_actual" class="form-label">Stock Actual *</label>
                            <input type="number" class="form-control" id="stock_actual" name="stock_actual" 
                                   min="0" value="<?php echo $producto->stock_actual; ?>" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" 
                                   min="0" value="<?php echo $producto->stock_minimo; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <?php 
                                if($categorias):
                                    while($cat = $categorias->fetch()): 
                                ?>
                                    <option value="<?php echo $cat['id']; ?>"
                                            <?php echo ($cat['id'] == $producto->categoria_id) ? 'selected' : ''; ?>>
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
                                    <option value="<?php echo $prov['id']; ?>"
                                            <?php echo ($prov['id'] == $producto->proveedor_id) ? 'selected' : ''; ?>>
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
                            <i class="bi bi-save"></i> Actualizar Producto
                        </button>
                        <a href="../../index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <a href="?eliminar=1&id=<?php echo $producto->id; ?>" 
                           class="btn btn-danger float-end" 
                           onclick="return confirm('¿Está seguro de eliminar este producto?')">
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