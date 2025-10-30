<?php
/**
 * Configuración de Autenticación
 * Funciones auxiliares para manejo de sesiones y permisos
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verificar si el usuario está autenticado
 */
function estaAutenticado() {
    return isset($_SESSION['usuario_id']) && isset($_SESSION['rol']);
}

/**
 * Obtener datos del usuario en sesión
 */
function obtenerUsuario() {
    if(estaAutenticado()) {
        return [
            'id' => $_SESSION['usuario_id'],
            'usuario' => $_SESSION['usuario'],
            'nombre' => $_SESSION['nombre_completo'],
            'rol' => $_SESSION['rol']
        ];
    }
    return null;
}

/**
 * Verificar si el usuario tiene permiso para un módulo
 */
function tienePermiso($modulo) {
    if(!estaAutenticado()) {
        return false;
    }
    
    require_once __DIR__ . '/../models/Usuario.php';
    return Usuario::tienePermiso($_SESSION['rol'], $modulo);
}

/**
 * Redirigir al login si no está autenticado
 */
function requiereAutenticacion() {
    if(!estaAutenticado()) {
        header('Location: ' . getRutaBase() . '/auth/login.php');
        exit();
    }
}

/**
 * Redirigir si no tiene permiso para el módulo
 */
function requierePermiso($modulo) {
    requiereAutenticacion();
    
    if(!tienePermiso($modulo)) {
        header('Location: ' . getRutaBase() . '/auth/sin_permiso.php');
        exit();
    }
}

/**
 * Cerrar sesión
 */
function cerrarSesion() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: ' . getRutaBase() . '/auth/login.php');
    exit();
}

/**
 * Obtener ruta base del proyecto
 */
function getRutaBase() {
    // Detectar si estamos en una subcarpeta (views/productos, views/clientes, etc)
    $scriptPath = $_SERVER['SCRIPT_NAME'];
    $pathParts = explode('/', $scriptPath);
    
    // Contar cuántos niveles hay desde la raíz
    $depth = 0;
    foreach($pathParts as $part) {
        if($part !== '' && $part !== 'index.php' && $part !== 'login.php') {
            $depth++;
        }
    }
    
    // Si estamos en views/productos o views/clientes, necesitamos subir 2 niveles
    if(strpos($scriptPath, '/views/') !== false) {
        return '../..';
    }
    
    // Si estamos en la raíz
    return '.';
}

/**
 * Verificar rol específico
 */
function esAdmin() {
    return estaAutenticado() && $_SESSION['rol'] === 'admin';
}

/**
 * Verificar rol específico
 */
function esVendedor() {
    return estaAutenticado() && $_SESSION['rol'] === 'vendedor';
}
