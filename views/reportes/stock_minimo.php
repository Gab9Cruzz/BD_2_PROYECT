<?php
require_once '../../config/conexion.php';

$database = new Conexion();
$db = $database->getConnection();

// Usar la VISTA mejorada en lugar del modelo
$query = "SELECT * FROM vista_stock_bajo";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Stock Mínimo - Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
        .badge-sin-stock { background-color: #dc3545; }
        .badge-critico { background-color: #fd7e14; }
        .badge-minimo { background-color: #ffc107; color: #000; }
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
                <h2><i class="bi bi-exclamation-triangle"></i> Reporte de Stock Bajo Mínimo</h2>
                <p class="text-muted">Usando Vista de Base de Datos: <code>vista_stock_bajo</code></p>
            </div>
            <div class="col text-end">
                <button onclick="window.print()" class="btn btn-success">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
                <a href="../../index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-data"></i> Productos con Stock Bajo o en Mínimo
                </h5>
                <small class="text-muted">Fecha: <?php echo date('d/m/Y H:i'); ?></small>
            </div>
            <div class="card-body">
                <?php 
                $count = 0;
                if($stmt && $stmt->rowCount() > 0):
                    $count = $stmt->rowCount();
                ?>
                    <div class="alert alert-warning no-print">
                        <i class="bi bi-info-circle"></i> 
                        Se encontraron <strong><?php echo $count; ?></strong> productos con stock bajo o en mínimo.
                        Es necesario realizar un pedido urgente.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Talla</th>
                                    <th>Color</th>
                                    <th>Categoría</th>
                                    <th>Proveedor</th>
                                    <th>Teléfono Proveedor</th>
                                    <th class="text-center">Stock Actual</th>
                                    <th class="text-center">Stock Mínimo</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while($row = $stmt->fetch()): 
                                    // El estado_stock ya viene de la VISTA
                                    $estadoClass = '';
                                    
                                    if($row['estado_stock'] == 'SIN STOCK') {
                                        $estadoClass = 'badge-sin-stock';
                                    } elseif($row['estado_stock'] == 'CRÍTICO') {
                                        $estadoClass = 'badge-critico';
                                    } else {
                                        $estadoClass = 'badge-minimo';
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $row['id_producto']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['nombre']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['talla']); ?></td>
                                        <td><?php echo htmlspecialchars($row['color']); ?></td>
                                        <td><?php echo htmlspecialchars($row['categoria']); ?></td>
                                        <td><?php echo htmlspecialchars($row['proveedor'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($row['telefono_proveedor'] ?? 'N/A'); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?php echo $row['stock']; ?></span>
                                        </td>
                                        <td class="text-center"><?php echo $row['stock_minimo']; ?></td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $estadoClass; ?>">
                                                <?php echo $row['estado_stock']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <strong>Recomendación:</strong> Contacte a los proveedores para reponer los productos listados.
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> 
                        ¡Excelente! Todos los productos tienen stock suficiente.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
