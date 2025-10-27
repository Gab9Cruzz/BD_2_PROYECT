<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario - Tienda de Ropa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <!-- Navbar -->
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
                        <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/productos/crear.php"><i class="bi bi-plus-circle"></i> Nuevo Producto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/clientes/crear.php"><i class="bi bi-person-plus"></i> Nuevo Cliente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/productos/editar.php"><i class="bi bi-pencil"></i> Editar Producto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/clientes/editar.php"><i class="bi bi-pencil-square"></i> Editar Cliente</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarReportes" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="views/ventas/generar.php">Generar Venta</a></li>
                            <li><a class="dropdown-item" href="views/reportes/ventas.php">Reporte de Ventas</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Hero Section -->
            <div class="col-12 mb-4">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body text-center py-5">
                        <h1 class="display-4"><i class="bi bi-shop-window"></i> Sistema de Inventario</h1>
                        <p class="lead">Gestión completa para tu tienda de ropa</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards - 6 Pantallas Requeridas -->
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

            <!-- Quick Actions - 6 Pantallas -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Sistema de 6 Pantallas (2+2+2)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary"><i class="bi bi-download"></i> INGRESO DE DATOS</h6>
                                <ul class="list-unstyled">
                                    <li><a href="views/productos/crear.php" class="text-decoration-none">1. Nuevo Producto</a></li>
                                    <li><a href="views/clientes/crear.php" class="text-decoration-none">2. Nuevo Cliente</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-warning"><i class="bi bi-pencil"></i> ACTUALIZACIÓN DE DATOS</h6>
                                <ul class="list-unstyled">
                                    <li><a href="views/productos/editar.php" class="text-decoration-none">3. Editar Producto</a></li>
                                    <li><a href="views/clientes/editar.php" class="text-decoration-none">4. Editar Cliente</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-danger"><i class="bi bi-file-earmark-text"></i> REPORTES DE DATOS</h6>
                                <ul class="list-unstyled">
                                    <li><a href="views/ventas/generar.php" class="text-decoration-none">5. Generar Venta</a></li>
                                    <li><a href="views/reportes/ventas.php" class="text-decoration-none">6. Reporte de Ventas</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-4 bg-light text-center">
        <div class="container">
            <p class="text-muted mb-0">
                &copy; 2025 Sistema de Inventario - Tienda de Ropa | 
                Desarrollado con <i class="bi bi-heart-fill text-danger"></i> usando PHP y Bootstrap
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
