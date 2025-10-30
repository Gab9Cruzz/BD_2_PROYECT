# 🎨 Estructura Visual del Proyecto

## 📂 Árbol de Directorios Completo

```
Proyecto_PHP/
│
├── 📁 auth/                           ← AUTENTICACIÓN (NUEVA CARPETA)
│   ├── 🔐 login.php                   # Inicio de sesión
│   ├── 🚪 logout.php                  # Cerrar sesión
│   └── 🛡️ sin_permiso.php             # Acceso denegado
│
├── 📁 config/                         ← CONFIGURACIÓN
│   ├── ⚙️ auth.php                    # Funciones de autenticación
│   └── 🔌 conexion.php                # Conexión a base de datos
│
├── 📁 models/                         ← MODELOS (Lógica de negocio)
│   ├── 👤 Usuario.php                 # Gestión de usuarios
│   ├── 🏷️ Categoria.php               # Gestión de categorías
│   ├── 👥 Cliente.php                 # Gestión de clientes
│   ├── 💰 FacturaVenta.php            # Gestión de facturas
│   ├── 📦 MovimientoInventario.php    # Movimientos de inventario
│   ├── 📦 Producto.php                # Gestión de productos
│   └── 🚚 Proveedor.php               # Gestión de proveedores
│
├── 📁 views/                          ← VISTAS (Interfaz de usuario)
│   │
│   ├── 📁 productos/
│   │   ├── ➕ crear.php               # Crear producto [Admin + Vendedor]
│   │   └── ✏️ editar.php              # Editar producto [Solo Admin]
│   │
│   ├── 📁 clientes/
│   │   ├── ➕ crear.php               # Crear cliente [Admin + Vendedor]
│   │   └── ✏️ editar.php              # Editar cliente [Solo Admin]
│   │
│   ├── 📁 ventas/
│   │   └── 🛒 generar.php             # Generar venta [Admin + Vendedor]
│   │
│   └── 📁 reportes/
│       ├── 📊 ventas.php              # Reporte de ventas [Solo Admin]
│       └── ⚠️ stock_minimo.php        # Reporte stock bajo [Solo Admin]
│
├── 📁 public/                         ← RECURSOS PÚBLICOS
│   ├── 📁 css/
│   │   └── 🎨 style.css               # Estilos personalizados
│   └── 📁 js/
│       └── ⚡ main.js                  # JavaScript principal
│
├── 📁 sql/                            ← BASE DE DATOS
│   └── 🗄️ inventario_tienda.sql      # Script completo de BD
│
├── 🏠 INDEX.PHP                       # Dashboard principal (requiere login)
│
└── 📚 DOCUMENTACIÓN
    ├── 📖 README.md                   # Documentación principal
    ├── 📋 CASOS_DE_PRUEBA.md
    ├── 🔐 SISTEMA_AUTENTICACION.md    # Sistema de login
    ├── 🧪 GUIA_PRUEBAS_LOGIN.md
    ├── 📁 REORGANIZACION_AUTH.md      # Esta reorganización
    └── ... (otros archivos .md)
```

---

## 🔗 Flujo de Navegación

### 🔑 Sin Autenticación
```
Usuario accede → http://localhost/Proyecto_PHP/
                      ↓
              Redirige automáticamente
                      ↓
              auth/login.php (Formulario de login)
```

### ✅ Con Autenticación Exitosa
```
Usuario ingresa credenciales
        ↓
auth/login.php verifica usuario/password
        ↓
Guarda datos en $_SESSION
        ↓
Redirige a INDEX.PHP (Dashboard)
        ↓
Muestra módulos según permisos del rol
```

### 🚫 Acceso Sin Permiso
```
Usuario intenta acceder a módulo restringido
        ↓
Sistema verifica permiso con tienePermiso()
        ↓
NO tiene permiso
        ↓
Redirige a auth/sin_permiso.php
        ↓
Opciones: [Volver al Inicio] [Cerrar Sesión]
```

### 🚪 Cierre de Sesión
```
Usuario hace click en "Cerrar Sesión"
        ↓
Ejecuta auth/logout.php
        ↓
Destruye $_SESSION
        ↓
Redirige a auth/login.php
```

---

## 🎯 Mapeo de Roles y Permisos

### 👨‍💼 Admin (7 módulos)
```
┌─────────────────────────────────────────┐
│          ADMINISTRADOR                   │
├─────────────────────────────────────────┤
│  ✅ Nuevo Producto                       │
│  ✅ Editar Producto                      │
│  ✅ Nuevo Cliente                        │
│  ✅ Editar Cliente                       │
│  ✅ Generar Venta                        │
│  ✅ Reporte de Ventas                    │
│  ✅ Reporte Stock Mínimo                 │
└─────────────────────────────────────────┘
```

### 🛒 Vendedor (3 módulos)
```
┌─────────────────────────────────────────┐
│            VENDEDOR                      │
├─────────────────────────────────────────┤
│  ✅ Nuevo Producto                       │
│  ✅ Nuevo Cliente                        │
│  ✅ Generar Venta                        │
│  ❌ Editar Producto                      │
│  ❌ Editar Cliente                       │
│  ❌ Reporte de Ventas                    │
│  ❌ Reporte Stock Mínimo                 │
└─────────────────────────────────────────┘
```

---

## 🔐 Sistema de Seguridad por Capas

```
┌──────────────────────────────────────────────────┐
│  CAPA 1: Protección de INDEX.PHP                 │
│  → requiereAutenticacion()                       │
│  → Si no hay sesión: redirige a auth/login.php  │
└──────────────────────────────────────────────────┘
                    ↓
┌──────────────────────────────────────────────────┐
│  CAPA 2: Navbar Dinámica                         │
│  → Muestra solo enlaces permitidos               │
│  → tienePermiso('modulo')                        │
└──────────────────────────────────────────────────┘
                    ↓
┌──────────────────────────────────────────────────┐
│  CAPA 3: Protección por Vista                    │
│  → Cada vista verifica:                          │
│    requierePermiso('modulo_especifico')          │
│  → Si no tiene permiso: auth/sin_permiso.php    │
└──────────────────────────────────────────────────┘
```

---

## 📊 Estadísticas del Proyecto

```
📦 Total de Archivos PHP:        25+
📁 Carpetas principales:         7
🔐 Archivos de autenticación:    3 (en auth/)
🎨 Archivos de vista:            7
🔧 Modelos de negocio:           7
⚙️ Archivos de configuración:    2
📚 Archivos de documentación:    10+
👥 Usuarios precargados:         2 (admin, vendedor1)
🛡️ Permisos definidos:           7
🎭 Roles implementados:          2 (admin, vendedor)
```

---

## 🎨 Paleta de Colores del Sistema

### Login & Autenticación
```css
Gradiente Principal:  #667eea → #764ba2 (Morado azulado)
Hover Effect:         rgba(102, 126, 234, 0.4)
Focus Border:         #667eea
```

### Dashboard
```css
Primary:    #0d6efd (Azul Bootstrap)
Success:    #198754 (Verde)
Warning:    #ffc107 (Amarillo)
Danger:     #dc3545 (Rojo)
Info:       #0dcaf0 (Cian)
Secondary:  #6c757d (Gris)
```

### Sin Permiso
```css
Gradiente Error: #f093fb → #f5576c (Rosa a Rojo)
Icono Error:     #dc3545 (Rojo peligro)
```

---

## 🚀 Comandos Rápidos

### Iniciar Desarrollo
```bash
# En PowerShell
cd C:\xampp\htdocs\Proyecto_PHP
explorer http://localhost/Proyecto_PHP/
```

### Ver Logs de Apache (si hay errores)
```bash
Get-Content C:\xampp\apache\logs\error.log -Tail 20
```

### Verificar Estructura
```bash
Get-ChildItem -Recurse -Directory | Format-Table FullName
```

### Listar Archivos PHP
```bash
Get-ChildItem -Recurse -Filter "*.php" | Format-Table FullName, Length
```

---

## 📝 Notas Importantes

1. **📂 Carpeta `auth/`**: Todos los archivos de autenticación están aquí
2. **🔒 Sesiones PHP**: Se manejan automáticamente en cada vista
3. **🛡️ Protección**: Todas las vistas requieren autenticación
4. **📊 Roles**: Admin tiene acceso completo, Vendedor limitado
5. **🔄 Rutas**: Todas actualizadas a la nueva estructura
6. **✅ Compatibilidad**: Sistema 100% funcional después de la reorganización

---

## 🎉 Resultado Final

```
ANTES:                          DESPUÉS:
                               
Proyecto_PHP/                  Proyecto_PHP/
├── login.php                  ├── auth/
├── logout.php                 │   ├── login.php
├── sin_permiso.php            │   ├── logout.php
├── index.php                  │   └── sin_permiso.php
├── config/                    ├── config/
├── models/                    ├── models/
├── views/                     ├── views/
└── ...                        ├── INDEX.PHP
                               └── ...

❌ Desordenado                 ✅ Organizado
❌ Difícil de encontrar        ✅ Todo en su lugar
❌ Raíz saturada               ✅ Raíz limpia
```

---

**Sistema reorganizado exitosamente** ✨

