<?php
require_once '../../config/conexion.php';

$database = new Conexion();
$db = $database->getConnection();

// Obtener fechas del formulario o usar valores por defecto
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

$result = null;
$estadisticas = null;

if(isset($_GET['generar'])) {
    // Usar STORED PROCEDURE en lugar del modelo
    $query = "CALL sp_reporte_ventas_fechas(:fecha_inicio, :fecha_fin)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    $stmt->execute();
    
    // Primer resultado: lista de ventas
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Segundo resultado: estadísticas
    $stmt->nextRowset();
    $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
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
                <p class="text-muted">Usando Stored Procedure: <code>sp_reporte_ventas_fechas</code></p>
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
            <?php if($estadisticas): ?>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <h3><?php echo $estadisticas['total_ventas']; ?></h3>
                            <small>Total Ventas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h3>$ <?php echo number_format($estadisticas['ingresos_totales'], 2); ?></h3>
                            <small>Ingresos Totales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-info text-white">
                        <div class="card-body">
                            <h3>$ <?php echo number_format($estadisticas['promedio_venta'], 2); ?></h3>
                            <small>Promedio por Venta</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-warning text-dark">
                        <div class="card-body">
                            <h6 class="mb-0">Rango</h6>
                            <small>$ <?php echo number_format($estadisticas['venta_minima'], 2); ?> - 
                                   $ <?php echo number_format($estadisticas['venta_maxima'], 2); ?></small>
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
                                        <th>Correo</th>
                                        <th>Método de Pago</th>
                                        <th class="text-center">Items</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($result as $row): ?>
                                        <tr>
                                            <td><strong>#<?php echo $row['id_factura']; ?></strong></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                                            <td><?php echo htmlspecialchars($row['cliente'] ?? 'Cliente genérico'); ?></td>
                                            <td><?php echo htmlspecialchars($row['correo'] ?? 'N/A'); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo htmlspecialchars($row['metodo_pago']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center"><?php echo $row['cantidad_items']; ?></td>
                                            <td class="text-end">$ <?php echo number_format($row['total'], 2); ?></td>
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
</body>
</html>
