# 🔐 Sistema de Autenticación y Roles

## Descripción General
Sistema de login implementado con roles de usuario (Admin y Vendedor) con permisos diferenciados para cada módulo.

---

## 👥 Usuarios del Sistema

### Credenciales de Acceso

| Usuario | Contraseña | Rol | Acceso |
|---------|-----------|-----|--------|
| `admin` | `admin` | Administrador | Acceso total a todos los módulos |
| `vendedor1` | `vendedor` | Vendedor | Acceso limitado (ver tabla de permisos) |

---

## 📋 Tabla de Permisos por Rol

| Módulo | Admin | Vendedor | Código Permiso |
|--------|-------|----------|----------------|
| **Nuevo Producto** | ✅ | ✅ | `productos_crear` |
| **Editar Producto** | ✅ | ❌ | `productos_editar` |
| **Nuevo Cliente** | ✅ | ✅ | `clientes_crear` |
| **Editar Cliente** | ✅ | ❌ | `clientes_editar` |
| **Generar Venta** | ✅ | ✅ | `ventas_generar` |
| **Reporte de Ventas** | ✅ | ❌ | `reportes_ventas` |
| **Reporte Stock Mínimo** | ✅ | ❌ | `reportes_stock` |

---

## 🏗️ Arquitectura del Sistema

### Archivos Creados

```
Proyecto_PHP/
├── config/
│   └── auth.php                    # Funciones de autenticación
├── models/
│   └── Usuario.php                 # Modelo de usuario
├── login.php                       # Página de login
├── logout.php                      # Cerrar sesión
├── sin_permiso.php                 # Página de acceso denegado
└── INDEX.PHP                       # Dashboard principal (actualizado)
```

### Archivos Modificados

```
views/
├── productos/
│   ├── crear.php                   # ✅ Protegido con permiso
│   └── editar.php                  # ✅ Protegido con permiso
├── clientes/
│   ├── crear.php                   # ✅ Protegido con permiso
│   └── editar.php                  # ✅ Protegido con permiso
├── ventas/
│   └── generar.php                 # ✅ Protegido con permiso
└── reportes/
    ├── ventas.php                  # ✅ Protegido con permiso
    └── stock_minimo.php            # ✅ Protegido con permiso
```

---

## 🔧 Funciones Principales

### config/auth.php

```php
// Verificar si está autenticado
estaAutenticado() : bool

// Obtener datos del usuario en sesión
obtenerUsuario() : array

// Verificar si tiene permiso para un módulo
tienePermiso($modulo) : bool

// Requerir autenticación (redirige a login si no está autenticado)
requiereAutenticacion()

// Requerir permiso específico (redirige si no tiene permiso)
requierePermiso($modulo)

// Cerrar sesión
cerrarSesion()

// Verificar si es administrador
esAdmin() : bool

// Verificar si es vendedor
esVendedor() : bool
```

### models/Usuario.php

```php
// Autenticar usuario
login($usuario, $password) : array|false

// Verificar permiso estático
Usuario::tienePermiso($rol, $modulo) : bool

// Obtener todos los usuarios
obtenerTodos() : PDOStatement
```

---

## 🔒 Cómo Proteger una Página

Para proteger cualquier página del sistema, agregar al inicio del archivo:

```php
<?php
session_start();
require_once '../../config/auth.php';

// Opción 1: Solo requerir autenticación
requiereAutenticacion();

// Opción 2: Requerir permiso específico
requierePermiso('nombre_permiso');
?>
```

---

## 🚀 Flujo de Autenticación

### 1. Inicio de Sesión

```mermaid
Usuario → login.php → Verificar credenciales → Guardar en $_SESSION → Redirigir a index.php
```

1. Usuario ingresa credenciales en `login.php`
2. Sistema verifica con `Usuario::login()`
3. Si es válido, guarda datos en sesión:
   - `$_SESSION['usuario_id']`
   - `$_SESSION['usuario']`
   - `$_SESSION['nombre_completo']`
   - `$_SESSION['rol']`
4. Redirige al dashboard principal

### 2. Acceso a Módulos

```mermaid
Usuario → Módulo → requierePermiso() → Verificar rol → Mostrar contenido O Redirigir a sin_permiso.php
```

1. Usuario intenta acceder a un módulo
2. Sistema ejecuta `requierePermiso('modulo')`
3. Verifica si el rol tiene permiso
4. Si tiene permiso: Muestra el módulo
5. Si no tiene permiso: Redirige a `sin_permiso.php`

### 3. Cierre de Sesión

```mermaid
Usuario → logout.php → session_destroy() → Redirigir a login.php
```

---

## 🎨 Interfaz de Usuario

### Página de Login
- Diseño moderno con gradiente
- Campos: Usuario y Contraseña
- Mensaje de credenciales de prueba
- Alertas de error en caso de credenciales incorrectas

### Dashboard Principal (INDEX.PHP)
- Navbar con menú dinámico según permisos
- Información del usuario logueado en navbar
- Tarjetas de módulos (solo los permitidos)
- Lista de accesos rápidos filtrada por rol
- Badge indicando rol actual

### Página Sin Permiso
- Diseño elegante con icono de advertencia
- Mensaje claro de acceso denegado
- Muestra el rol actual del usuario
- Botones para volver al inicio o cerrar sesión

---

## 💾 Base de Datos

### Tabla: usuarios

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(150) NOT NULL,
    email VARCHAR(100),
    rol ENUM('admin', 'vendedor', 'almacenero') NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL
);
```

### Usuarios Precargados

```sql
INSERT INTO usuarios (usuario, password, nombre_completo, email, rol, activo) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Administrador Sistema', 'admin@tienda.ec', 'admin', 1),
('vendedor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Carlos Vendedor', 'vendedor@tienda.ec', 'vendedor', 1);
```

**Nota**: Las contraseñas están hasheadas, pero el sistema acepta `admin` y `vendedor` como contraseñas de prueba.

---

## 🔐 Seguridad Implementada

1. ✅ **Sesiones PHP**: Control de estado de autenticación
2. ✅ **Verificación de permisos**: En cada módulo protegido
3. ✅ **Redirecciones automáticas**: Si no está autenticado o sin permisos
4. ✅ **Protección contra acceso directo**: Todas las vistas protegidas
5. ✅ **Registro de último acceso**: Se actualiza en cada login
6. ✅ **Usuarios activos/inactivos**: Control con campo `activo`

---

## 📝 Ejemplos de Uso

### Agregar un Nuevo Permiso

1. Editar `models/Usuario.php`, método `tienePermiso()`:

```php
$permisos = [
    'admin' => [
        'productos_crear',
        'productos_editar',
        'nuevo_modulo'  // ← Agregar aquí
    ],
    'vendedor' => [
        'productos_crear'
    ]
];
```

2. Proteger la página del módulo:

```php
<?php
session_start();
require_once '../../config/auth.php';
requierePermiso('nuevo_modulo');
?>
```

3. Agregar al menú en `INDEX.PHP`:

```php
<?php if(tienePermiso('nuevo_modulo')): ?>
<li class="nav-item">
    <a class="nav-link" href="views/nuevo/modulo.php">
        <i class="bi bi-icon"></i> Nuevo Módulo
    </a>
</li>
<?php endif; ?>
```

---

## 🧪 Pruebas del Sistema

### Caso 1: Login como Admin
1. Ir a `http://localhost/Proyecto_PHP/login.php`
2. Ingresar: `admin` / `admin`
3. Verificar: Dashboard muestra **7 tarjetas** (todos los módulos)

### Caso 2: Login como Vendedor
1. Ir a `http://localhost/Proyecto_PHP/login.php`
2. Ingresar: `vendedor1` / `vendedor`
3. Verificar: Dashboard muestra **3 tarjetas** (crear productos, crear clientes, generar venta)

### Caso 3: Acceso Sin Permiso
1. Login como vendedor
2. Intentar acceder directamente a: `views/productos/editar.php`
3. Verificar: Redirige a `sin_permiso.php`

### Caso 4: Acceso Sin Login
1. Cerrar sesión
2. Intentar acceder directamente a cualquier módulo
3. Verificar: Redirige a `login.php`

---

## 🎯 Resumen

### ✅ Implementado
- Sistema de login funcional
- Control de roles (Admin y Vendedor)
- Permisos diferenciados por módulo
- Protección de todas las vistas
- Navbar dinámica según permisos
- Dashboard adaptativo por rol
- Página de acceso denegado
- Cierre de sesión
- Registro de último acceso

### 📊 Estadísticas
- **2 roles** implementados
- **7 permisos** diferentes
- **7 páginas** protegidas
- **4 archivos nuevos** creados
- **8 archivos** modificados

---

## 🔗 Navegación

- **Login**: `login.php`
- **Dashboard**: `index.php` (requiere login)
- **Logout**: `logout.php`
- **Sin Permiso**: `sin_permiso.php` (requiere login)

---

**Sistema implementado y listo para usar** ✨

