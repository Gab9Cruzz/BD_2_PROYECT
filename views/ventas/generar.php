<?php
session_start();
date_default_timezone_set('America/Guayaquil');

require_once '../../config/auth.php';
require_once '../../config/conexion.php';
require_once '../../models/FacturaVenta.php';
require_once '../../models/Cliente.php';
require_once '../../models/Producto.php';

// Requiere autenticación y permiso para generar ventas
requierePermiso('ventas_generar');

$database = new Conexion();
$db = $database->getConnection();

$factura = new FacturaVenta($db);
$cliente = new Cliente($db);
$producto = new Producto($db);

$mensaje = "";
$error = "";
$id_factura_creada = null;

// Procesar la venta
if($_POST && isset($_POST['generar_venta'])) {
    try {
        // Validar que haya productos
        if(empty($_POST['productos']) || empty($_POST['cantidades']) || empty($_POST['precios'])) {
            throw new Exception("Debe agregar al menos un producto a la venta");
        }

        $factura->numero_factura = 'FAC-' . date('YmdHis');
        $factura->cliente_id = !empty($_POST['cliente_id']) ? $_POST['cliente_id'] : null;
        $factura->fecha_emision = date('Y-m-d H:i:s');
        $factura->forma_pago = $_POST['forma_pago'];
        $factura->estado = 'pagada';
        $factura->observaciones = '';
        
        // Preparar detalles de venta
        $detalles = [];
        $subtotal_total = 0;
        $iva_total = 0;
        
        foreach($_POST['productos'] as $index => $id_producto) {
            if(!empty($id_producto) && !empty($_POST['cantidades'][$index]) && !empty($_POST['precios'][$index])) {
                $cantidad = (int)$_POST['cantidades'][$index];
                $precio = (float)$_POST['precios'][$index];
                $iva_porcentaje = isset($_POST['iva'][$index]) ? (float)$_POST['iva'][$index] : 15.00;
                
                $subtotal_item = $cantidad * $precio;
                $iva_valor = $subtotal_item * ($iva_porcentaje / 100);
                $total_item = $subtotal_item + $iva_valor;
                
                $detalles[] = [
                    'producto_id' => $id_producto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal_item,
                    'iva_porcentaje' => $iva_porcentaje,
                    'iva_valor' => $iva_valor,
                    'total' => $total_item
                ];
                
                $subtotal_total += $subtotal_item;
                $iva_total += $iva_valor;
            }
        }
        
        if(empty($detalles)) {
            throw new Exception("No se agregaron productos válidos a la venta");
        }
        
        $factura->subtotal = $subtotal_total;
        $factura->iva_total = $iva_total;
        $factura->total = $subtotal_total + $iva_total;
        
        // Crear la venta
        $id_factura_creada = $factura->crearVenta($detalles);
        
        if($id_factura_creada) {
            $mensaje = "Venta " . $factura->numero_factura . " generada exitosamente. Total: $ " . number_format($factura->total, 2);
        } else {
            throw new Exception("Error al generar la venta. Verifique el stock disponible.");
        }
        
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}

$clientes = $cliente->obtenerTodos();
$productos = $producto->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Venta - Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .producto-row { margin-bottom: 10px; }
        .total-venta { font-size: 1.5rem; font-weight: bold; }
    </style>
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
                <h2><i class="bi bi-cart-plus"></i> Generar Nueva Venta</h2>
            </div>
            <div class="col text-end">
                <a href="../../index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <?php if($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="" id="formVenta">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="cliente_id" class="form-label">Cliente (Opcional)</label>
                            <select class="form-select" id="cliente_id" name="cliente_id">
                                <option value="">Cliente genérico</option>
                                <?php 
                                if($clientes):
                                    while($cli = $clientes->fetch()): 
                                ?>
                                    <option value="<?php echo $cli['id']; ?>">
                                        <?php echo htmlspecialchars($cli['nombres'] . ' ' . $cli['apellidos']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                endif;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="forma_pago" class="form-label">Forma de Pago *</label>
                            <select class="form-select" id="forma_pago" name="forma_pago" required>
                                <option value="">Seleccione...</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta_debito">Tarjeta Débito</option>
                                <option value="tarjeta_credito">Tarjeta Crédito</option>
                                <option value="transferencia">Transferencia Bancaria</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <h5><i class="bi bi-bag"></i> Productos de la Venta</h5>
                    <div id="productos-container">
                        <div class="producto-row row align-items-end mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Producto</label>
                                <select class="form-select producto-select" name="productos[]" required onchange="actualizarPrecio(this)">
                                    <option value="">Seleccione producto...</option>
                                    <?php 
                                    // Recargar productos
                                    $productos = $producto->obtenerTodos();
                                    if($productos):
                                        while($prod = $productos->fetch()): 
                                    ?>
                                    <option value="<?php echo $prod['id']; ?>" 
                                            data-precio="<?php echo number_format($prod['precio_venta'], 2, '.', ''); ?>"
                                            data-stock="<?php echo $prod['stock_actual']; ?>"
                                            data-iva="<?php echo $prod['iva']; ?>">
                                        <?php echo htmlspecialchars($prod['nombre']); ?> - 
                                        <?php echo htmlspecialchars($prod['marca']); ?> 
                                        (Stock: <?php echo $prod['stock_actual']; ?>) - $ <?php echo number_format($prod['precio_venta'], 2); ?>
                                    </option>
                                    <?php 
                                        endwhile;
                                    endif;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Cant.</label>
                                <input type="number" class="form-control cantidad-input" name="cantidades[]" 
                                       min="1" value="1" required onchange="calcularTotal()">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">P. Unit.</label>
                                <input type="number" class="form-control precio-input" name="precios[]" 
                                       step="0.01" min="0.01" value="0.00" required onchange="calcularTotal()">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Subtotal</label>
                                <input type="text" class="form-control subtotal-input" readonly value="$ 0.00">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">IVA (15%)</label>
                                <input type="text" class="form-control iva-input" readonly value="$ 0.00">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarProducto(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="agregarProducto()">
                        <i class="bi bi-plus-circle"></i> Agregar Otro Producto
                    </button>

                    <hr>

                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td><strong>Subtotal:</strong></td>
                                            <td class="text-end" id="subtotal-venta">$ 0.00</td>
                                        </tr>
                                        <tr>
                                            <td><strong>IVA Total:</strong></td>
                                            <td class="text-end" id="iva-total-venta">$ 0.00</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td><strong>TOTAL:</strong></td>
                                            <td class="text-end"><strong id="total-venta">$ 0.00</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" name="generar_venta" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Generar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Template de producto para clonar
        const productoTemplate = document.querySelector('.producto-row').cloneNode(true);

        function agregarProducto() {
            const container = document.getElementById('productos-container');
            const nuevoProducto = productoTemplate.cloneNode(true);
            
            // Limpiar valores
            nuevoProducto.querySelector('.producto-select').value = '';
            nuevoProducto.querySelector('.cantidad-input').value = '1';
            nuevoProducto.querySelector('.precio-input').value = '0.00';
            nuevoProducto.querySelector('.subtotal-input').value = '$ 0.00';
            nuevoProducto.querySelector('.iva-input').value = '$ 0.00';
            nuevoProducto.setAttribute('data-iva', '15.00');
            
            container.appendChild(nuevoProducto);
            calcularTotal();
        }

        function eliminarProducto(btn) {
            const container = document.getElementById('productos-container');
            if(container.children.length > 1) {
                btn.closest('.producto-row').remove();
                calcularTotal();
            } else {
                alert('Debe haber al menos un producto en la venta');
            }
        }

        function actualizarPrecio(select) {
            const row = select.closest('.producto-row');
            const option = select.options[select.selectedIndex];
            const precio = option.getAttribute('data-precio') || '0';
            const stock = option.getAttribute('data-stock') || '0';
            const iva = option.getAttribute('data-iva') || '15.00';
            
            const cantidadInput = row.querySelector('.cantidad-input');
            cantidadInput.max = stock;
            
            // Guardar el IVA como atributo data en la fila
            row.setAttribute('data-iva', iva);
            
            if(parseInt(stock) === 0) {
                alert('Este producto no tiene stock disponible');
                select.value = '';
                row.querySelector('.precio-input').value = '0.00';
            } else {
                row.querySelector('.precio-input').value = precio;
            }
            
            calcularSubtotal(row);
            calcularTotal();
        }

        function calcularSubtotal(row) {
            const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
            const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
            const iva_porcentaje = parseFloat(row.getAttribute('data-iva')) || 15.00;
            
            const subtotal = cantidad * precio;
            const iva_valor = subtotal * (iva_porcentaje / 100);
            
            row.querySelector('.subtotal-input').value = '$ ' + subtotal.toFixed(2);
            row.querySelector('.iva-input').value = '$ ' + iva_valor.toFixed(2) + ' (' + iva_porcentaje + '%)';
        }

        function calcularTotal() {
            const rows = document.querySelectorAll('.producto-row');
            let subtotal_total = 0;
            let iva_total = 0;
            
            rows.forEach(row => {
                calcularSubtotal(row);
                const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
                const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
                const iva_porcentaje = parseFloat(row.getAttribute('data-iva')) || 15.00;
                
                const subtotal = cantidad * precio;
                const iva_valor = subtotal * (iva_porcentaje / 100);
                
                subtotal_total += subtotal;
                iva_total += iva_valor;
            });
            
            const total_general = subtotal_total + iva_total;
            
            document.getElementById('subtotal-venta').textContent = '$ ' + subtotal_total.toFixed(2);
            document.getElementById('iva-total-venta').textContent = '$ ' + iva_total.toFixed(2);
            document.getElementById('total-venta').textContent = '$ ' + total_general.toFixed(2);
        }

        // Inicializar cálculo al cargar
        document.addEventListener('DOMContentLoaded', function() {
            calcularTotal();
            
            // Agregar listeners a los inputs iniciales
            document.querySelectorAll('.cantidad-input, .precio-input').forEach(input => {
                input.addEventListener('input', calcularTotal);
            });
        });
    </script>
</body>
</html>
