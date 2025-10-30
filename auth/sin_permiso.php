<?php
session_start();
require_once '../config/auth.php';
requiereAutenticacion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sin Permiso - Sistema de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card error-card">
                    <div class="card-body text-center p-5">
                        <div class="text-danger mb-4">
                            <i class="bi bi-shield-exclamation" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="text-danger mb-3">Acceso Denegado</h2>
                        <p class="lead mb-4">
                            No tienes permiso para acceder a este módulo.
                        </p>
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle"></i>
                            Tu rol actual es: <strong><?php echo ucfirst($_SESSION['rol']); ?></strong>
                        </div>
                        <div class="mt-4">
                            <a href="../index.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-house"></i> Volver al Inicio
                            </a>
                            <a href="logout.php" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
