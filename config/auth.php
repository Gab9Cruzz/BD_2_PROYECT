<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaAutenticado() {
    return isset($_SESSION['usuario_id']) && isset($_SESSION['rol']);
}

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

function tienePermiso($modulo) {
    if(!estaAutenticado()) {
        return false;
    }
    
    require_once __DIR__ . '/../models/Usuario.php';
    return Usuario::tienePermiso($_SESSION['rol'], $modulo);
}

function requiereAutenticacion() {
    if(!estaAutenticado()) {
        header('Location: ' . getRutaBase() . '/auth/login.php');
        exit();
    }
}

function requierePermiso($modulo) {
    requiereAutenticacion();
    
    if(!tienePermiso($modulo)) {
        header('Location: ' . getRutaBase() . '/auth/sin_permiso.php');
        exit();
    }
}

function cerrarSesion() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: ' . getRutaBase() . '/auth/login.php');
    exit();
}

function getRutaBase() {
    $scriptPath = $_SERVER['SCRIPT_NAME'];
    $pathParts = explode('/', $scriptPath);
    
    $depth = 0;
    foreach($pathParts as $part) {
        if($part !== '' && $part !== 'index.php' && $part !== 'login.php') {
            $depth++;
        }
    }
    
    if(strpos($scriptPath, '/views/') !== false) {
        return '../..';
    }
    
    return '.';
}

function esAdmin() {
    return estaAutenticado() && $_SESSION['rol'] === 'admin';
}

function esVendedor() {
    return estaAutenticado() && $_SESSION['rol'] === 'vendedor';
}
