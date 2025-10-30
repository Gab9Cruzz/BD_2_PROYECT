<?php
session_start();

// Si ya está autenticado, redirigir al index
if(isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}

$error = '';

// Procesar login
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/conexion.php';
    require_once '../models/Usuario.php';
    
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if(empty($usuario) || empty($password)) {
        $error = 'Por favor, complete todos los campos';
    } else {
        $database = new Conexion();
        $db = $database->getConnection();
        $usuarioModel = new Usuario($db);
        
        $resultado = $usuarioModel->login($usuario, $password);
        
        if($resultado) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $resultado['id'];
            $_SESSION['usuario'] = $resultado['usuario'];
            $_SESSION['nombre_completo'] = $resultado['nombre_completo'];
            $_SESSION['rol'] = $resultado['rol'];
            
            // Redirigir al index
            header('Location: ../index.php');
            exit();
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 450px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-card">
                    <div class="login-header">
                        <i class="bi bi-shop-window" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-0">Sistema de Inventario</h3>
                        <p class="mb-0">Tienda de Ropa</p>
                    </div>
                    <div class="login-body bg-white">
                        <h4 class="text-center mb-4">Iniciar Sesión</h4>
                        
                        <?php if($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person"></i> Usuario
                                </label>
                                <input type="text" class="form-control" name="usuario" 
                                       placeholder="Ingrese su usuario" required autofocus>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-lock"></i> Contraseña
                                </label>
                                <input type="password" class="form-control" name="password" 
                                       placeholder="Ingrese su contraseña" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </form>

                        <div class="mt-4 pt-3 border-top text-center">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Credenciales de prueba:<br>
                                <strong>Admin:</strong> usuario: admin / contraseña: admin<br>
                                <strong>Vendedor:</strong> usuario: vendedor1 / contraseña: vendedor
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
