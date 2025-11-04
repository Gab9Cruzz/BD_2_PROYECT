<?php
session_start();
require_once 'config/auth.php';

requiereAutenticacion();

$usuario = obtenerUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario - Tienda de Ropa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-shop"></i> Inventario Tienda de Ropa
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="bi bi-house-door"></i> Inicio</a>
                    </li>
                    
                    <?php if(tienePermiso('productos_crear')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="views/productos/crear.php"><i class="bi bi-plus-circle"></i> Nuevo Producto</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(tienePermiso('clientes_crear')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="views/clientes/crear.php"><i class="bi bi-person-plus"></i> Nuevo Cliente</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(tienePermiso('productos_editar')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="views/productos/editar.php"><i class="bi bi-pencil"></i> Editar Producto</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(tienePermiso('clientes_editar')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="views/clientes/editar.php"><i class="bi bi-pencil-square"></i> Editar Cliente</a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarReportes" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                        </a>
                        <ul class="dropdown-menu">
                            <?php if(tienePermiso('ventas_generar')): ?>
                            <li><a class="dropdown-item" href="views/ventas/generar.php">Generar Venta</a></li>
                            <?php endif; ?>
                            
                            <?php if(tienePermiso('reportes_ventas')): ?>
                            <li><a class="dropdown-item" href="views/reportes/ventas.php">Reporte de Ventas</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($usuario['nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-item-text">
                                <small class="text-muted">
                                    <i class="bi bi-shield"></i> Rol: <strong><?php echo ucfirst($usuario['rol']); ?></strong>
                                </small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="auth/logout.php">
                                <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body text-center py-5">
                        <h1 class="display-4"><i class="bi bi-shop-window"></i> Sistema de Inventario</h1>
                        <p class="lead">Gestión completa para tu tienda de ropa</p>
                        <div class="badge bg-light text-dark fs-6 mt-2">
                            <i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($usuario['nombre']); ?> 
                            | Rol: <?php echo ucfirst($usuario['rol']); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(tienePermiso('productos_crear')): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-primary-soft mb-3">
                            <i class="bi bi-plus-circle fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title">Nuevo Producto</h5>
                        <p class="card-text text-muted">Ingreso de datos</p>
                        <a href="views/productos/crear.php" class="btn btn-outline-primary btn-sm">
                            Ingresar <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(tienePermiso('clientes_crear')): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-success-soft mb-3">
                            <i class="bi bi-person-plus fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title">Nuevo Cliente</h5>
                        <p class="card-text text-muted">Ingreso de datos</p>
                        <a href="views/clientes/crear.php" class="btn btn-outline-success btn-sm">
                            Ingresar <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(tienePermiso('productos_editar')): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-warning-soft mb-3">
                            <i class="bi bi-pencil fs-1 text-warning"></i>
                        </div>
                        <h5 class="card-title">Editar Producto</h5>
                        <p class="card-text text-muted">Actualización de datos</p>
                        <a href="views/productos/editar.php" class="btn btn-outline-warning btn-sm">
                            Actualizar <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(tienePermiso('clientes_editar')): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-info-soft mb-3">
                            <i class="bi bi-pencil-square fs-1 text-info"></i>
                        </div>
                        <h5 class="card-title">Editar Cliente</h5>
                        <p class="card-text text-muted">Actualización de datos</p>
                        <a href="views/clientes/editar.php" class="btn btn-outline-info btn-sm">
                            Actualizar <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(tienePermiso('ventas_generar')): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-danger-soft mb-3">
                            <i class="bi bi-cart-plus fs-1 text-danger"></i>
                        </div>
                        <h5 class="card-title">Generar Venta</h5>
                        <p class="card-text text-muted">Reporte de datos</p>
                        <a href="views/ventas/generar.php" class="btn btn-outline-danger btn-sm">
                            Crear Venta <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(tienePermiso('reportes_ventas')): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-secondary-soft mb-3">
                            <i class="bi bi-graph-up fs-1 text-secondary"></i>
                        </div>
                        <h5 class="card-title">Reporte de Ventas</h5>
                        <p class="card-text text-muted">Reporte de datos</p>
                        <a href="views/reportes/ventas.php" class="btn btn-outline-secondary btn-sm">
                            Ver Reporte <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning-charge"></i> 
                            Módulos Disponibles para: <strong><?php echo ucfirst($usuario['rol']); ?></strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if(tienePermiso('productos_crear') || tienePermiso('clientes_crear')): ?>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary"><i class="bi bi-download"></i> INGRESO DE DATOS</h6>
                                <ul class="list-unstyled">
                                    <?php if(tienePermiso('productos_crear')): ?>
                                    <li><a href="views/productos/crear.php" class="text-decoration-none">
                                        <i class="bi bi-check-circle text-success"></i> Nuevo Producto
                                    </a></li>
                                    <?php endif; ?>
                                    
                                    <?php if(tienePermiso('clientes_crear')): ?>
                                    <li><a href="views/clientes/crear.php" class="text-decoration-none">
                                        <i class="bi bi-check-circle text-success"></i> Nuevo Cliente
                                    </a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(tienePermiso('productos_editar') || tienePermiso('clientes_editar')): ?>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-warning"><i class="bi bi-pencil"></i> ACTUALIZACIÓN DE DATOS</h6>
                                <ul class="list-unstyled">
                                    <?php if(tienePermiso('productos_editar')): ?>
                                    <li><a href="views/productos/editar.php" class="text-decoration-none">
                                        <i class="bi bi-check-circle text-success"></i> Editar Producto
                                    </a></li>
                                    <?php endif; ?>
                                    
                                    <?php if(tienePermiso('clientes_editar')): ?>
                                    <li><a href="views/clientes/editar.php" class="text-decoration-none">
                                        <i class="bi bi-check-circle text-success"></i> Editar Cliente
                                    </a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(tienePermiso('ventas_generar') || tienePermiso('reportes_ventas') || tienePermiso('reportes_stock')): ?>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-danger"><i class="bi bi-file-earmark-text"></i> REPORTES DE DATOS</h6>
                                <ul class="list-unstyled">
                                    <?php if(tienePermiso('ventas_generar')): ?>
                                    <li><a href="views/ventas/generar.php" class="text-decoration-none">
                                        <i class="bi bi-check-circle text-success"></i> Generar Venta
                                    </a></li>
                                    <?php endif; ?>
                                    
                                    <?php if(tienePermiso('reportes_ventas')): ?>
                                    <li><a href="views/reportes/ventas.php" class="text-decoration-none">
                                        <i class="bi bi-check-circle text-success"></i> Reporte de Ventas
                                    </a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if(esVendedor()): ?>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle"></i> 
                            Como <strong>Vendedor</strong>, tienes acceso a: Crear productos, Crear clientes y Generar ventas.
                        </div>
                        <?php endif; ?>
                        
                        <?php if(esAdmin()): ?>
                        <div class="alert alert-success mt-3">
                            <i class="bi bi-shield-check"></i> 
                            Como <strong>Administrador</strong>, tienes acceso total a todos los módulos del sistema.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 py-4 bg-light text-center">
        <div class="container">
            <p class="text-muted mb-0">
                Sistema de Inventario - Tienda de Ropa
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
