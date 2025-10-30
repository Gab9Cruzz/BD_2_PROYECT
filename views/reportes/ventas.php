<?php
session_start();
date_default_timezone_set('America/Guayaquil');

require_once '../../config/auth.php';
require_once '../../config/conexion.php';

// Requiere autenticación y permiso para ver reportes de ventas
requierePermiso('reportes_ventas');

$database = new Conexion();
$db = $database->getConnection();

// Obtener fechas del formulario o usar valores por defecto
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

$result = null;
$estadisticas = null;

if(isset($_GET['generar'])) {
    $query = "CALL sp_reporte_ventas_periodo(:fecha_inicio, :fecha_fin)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    $stmt->execute();
    
    // Primer resultado: lista de ventas
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Segundo resultado: estadísticas
    $stmt->nextRowset();
    $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Cerrar el cursor para permitir nuevas queries
    $stmt->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
        .btn-toggle-detalle {
            transition: all 0.3s ease;
        }
        .collapse {
            transition: height 0.35s ease;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .badge {
            font-size: 0.85em;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary no-print">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">
                <i class="bi bi-shop"></i> Inventario Tienda
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4 no-print">
            <div class="col">
                <h2><i class="bi bi-graph-up"></i> Reporte de Ventas por Fechas</h2>
                <p class="text-muted">Usando Stored Procedure: <code>sp_reporte_ventas_periodo</code></p>
            </div>
            <div class="col text-end">
                <?php if($result): ?>
                    <button onclick="window.print()" class="btn btn-success">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                <?php endif; ?>
                <a href="../../index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <!-- Formulario de filtros -->
        <div class="card shadow-sm mb-4 no-print">
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-2">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                   value="<?php echo $fecha_inicio; ?>" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="fecha_fin" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                   value="<?php echo $fecha_fin; ?>" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="submit" name="generar" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Generar Reporte
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($result): ?>
            <!-- Tarjetas de estadísticas -->
            <?php if($estadisticas && isset($estadisticas['total_facturas'])): ?>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <h3><?php echo $estadisticas['total_facturas']; ?></h3>
                            <small>Total Facturas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h3>$ <?php echo number_format($estadisticas['total_subtotal'], 2); ?></h3>
                            <small>Subtotal</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-info text-white">
                        <div class="card-body">
                            <h3>$ <?php echo number_format($estadisticas['total_iva'], 2); ?></h3>
                            <small>Total IVA</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-warning text-dark">
                        <div class="card-body">
                            <h3>$ <?php echo number_format($estadisticas['total_general'], 2); ?></h3>
                            <small>Total General</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-range"></i> 
                        Ventas del <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> 
                        al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?>
                    </h5>
                    <small class="text-muted">Reporte generado: <?php echo date('d/m/Y H:i'); ?></small>
                </div>
                <div class="card-body">
                    <?php 
                    if(count($result) > 0):
                    ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Factura #</th>
                                        <th>Fecha y Hora</th>
                                        <th>Cliente</th>
                                        <th>Forma de Pago</th>
                                        <th class="text-center">Items</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $contador = 0;
                                    foreach($result as $row): 
                                        $contador++;
                                        
                                        // Contar items de la factura
                                        $queryCount = "SELECT COUNT(*) as cantidad_items, f.id 
                                                      FROM detalle_factura df
                                                      INNER JOIN facturas_venta f ON df.factura_id = f.id
                                                      WHERE f.numero_factura = :numero_factura
                                                      GROUP BY f.id";
                                        $stmtCount = $db->prepare($queryCount);
                                        $stmtCount->bindParam(':numero_factura', $row['numero_factura']);
                                        $stmtCount->execute();
                                        $countData = $stmtCount->fetch(PDO::FETCH_ASSOC);
                                        $stmtCount->closeCursor();
                                        
                                        $cantidad_items = $countData ? $countData['cantidad_items'] : 0;
                                        $id_factura = $countData ? $countData['id'] : $contador;
                                    ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['numero_factura']); ?></strong></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_creacion'])); ?></td>
                                            <td><?php echo htmlspecialchars($row['cliente'] ?? 'Cliente genérico'); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $row['forma_pago']))); ?>
                                                </span>
                                            </td>
                                            <td class="text-center"><?php echo $cantidad_items; ?></td>
                                            <td class="text-end">$ <?php echo number_format($row['total'], 2); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary btn-toggle-detalle" type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#detalle<?php echo $id_factura; ?>" 
                                                        aria-expanded="false" 
                                                        aria-controls="detalle<?php echo $id_factura; ?>">
                                                    <i class="bi bi-eye"></i> <span class="btn-text">Ver</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="p-0 border-0">
                                                <div class="collapse" id="detalle<?php echo $id_factura; ?>">
                                                    <div class="p-3 bg-light border">
                                                        <h6 class="mb-3">
                                                            <i class="bi bi-box-seam"></i> Productos en Factura <?php echo htmlspecialchars($row['numero_factura']); ?>
                                                        </h6>
                                                        <?php 
                                                        // Cargar detalles aquí dentro del collapse
                                                        $queryDetalle = "SELECT df.*, p.nombre AS producto, p.marca 
                                                                        FROM detalle_factura df
                                                                        INNER JOIN productos p ON df.producto_id = p.id
                                                                        INNER JOIN facturas_venta f ON df.factura_id = f.id
                                                                        WHERE f.numero_factura = :numero_factura";
                                                        $stmtDetalle = $db->prepare($queryDetalle);
                                                        $stmtDetalle->bindParam(':numero_factura', $row['numero_factura']);
                                                        $stmtDetalle->execute();
                                                        $detalles = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);
                                                        $stmtDetalle->closeCursor();
                                                        
                                                        if(count($detalles) > 0): 
                                                        ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered table-striped mb-0">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th>Producto</th>
                                                                        <th>Marca</th>
                                                                        <th class="text-center">Cant.</th>
                                                                        <th class="text-end">P. Unit.</th>
                                                                        <th class="text-end">Subtotal</th>
                                                                        <th class="text-end">IVA</th>
                                                                        <th class="text-end">Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php 
                                                                    $total_productos = 0;
                                                                    $total_iva = 0;
                                                                    $total_general = 0;
                                                                    foreach($detalles as $det): 
                                                                        $total_productos += $det['subtotal'];
                                                                        $total_iva += $det['iva_valor'];
                                                                        $total_general += $det['total'];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo htmlspecialchars($det['producto']); ?></td>
                                                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($det['marca']); ?></span></td>
                                                                        <td class="text-center"><strong><?php echo $det['cantidad']; ?></strong></td>
                                                                        <td class="text-end">$ <?php echo number_format($det['precio_unitario'], 2); ?></td>
                                                                        <td class="text-end">$ <?php echo number_format($det['subtotal'], 2); ?></td>
                                                                        <td class="text-end">$ <?php echo number_format($det['iva_valor'], 2); ?> (<?php echo $det['iva_porcentaje']; ?>%)</td>
                                                                        <td class="text-end"><strong>$ <?php echo number_format($det['total'], 2); ?></strong></td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                    <tr class="table-info fw-bold">
                                                                        <td colspan="4" class="text-end">TOTALES:</td>
                                                                        <td class="text-end">$ <?php echo number_format($total_productos, 2); ?></td>
                                                                        <td class="text-end">$ <?php echo number_format($total_iva, 2); ?></td>
                                                                        <td class="text-end">$ <?php echo number_format($total_general, 2); ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <?php else: ?>
                                                        <div class="alert alert-warning mb-0">
                                                            <i class="bi bi-exclamation-triangle"></i> No se encontraron detalles para esta factura.
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            No se encontraron ventas en el rango de fechas seleccionado.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                Seleccione un rango de fechas y haga clic en "Generar Reporte" para ver las ventas.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mejorar la interacción de los botones de detalle
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener todos los botones de toggle
            const toggleButtons = document.querySelectorAll('.btn-toggle-detalle');
            
            toggleButtons.forEach(function(btn) {
                // Obtener el elemento collapse asociado
                const targetId = btn.getAttribute('data-bs-target');
                const collapseElement = document.querySelector(targetId);
                
                if (collapseElement) {
                    // Evento cuando se muestra el collapse
                    collapseElement.addEventListener('show.bs.collapse', function() {
                        const icon = btn.querySelector('i');
                        const text = btn.querySelector('.btn-text');
                        icon.className = 'bi bi-eye-slash';
                        text.textContent = 'Ocultar';
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-secondary');
                    });
                    
                    // Evento cuando se oculta el collapse
                    collapseElement.addEventListener('hide.bs.collapse', function() {
                        const icon = btn.querySelector('i');
                        const text = btn.querySelector('.btn-text');
                        icon.className = 'bi bi-eye';
                        text.textContent = 'Ver';
                        btn.classList.remove('btn-secondary');
                        btn.classList.add('btn-primary');
                    });
                }
            });
        });
    </script>
</body>
</html>
