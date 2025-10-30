# 🚀 Guía Rápida de Pruebas - Sistema de Autenticación

## 📋 Pasos para Probar el Sistema

### 1️⃣ Iniciar XAMPP
```
✅ Apache (Puerto 80)
✅ MySQL (Puerto 3306)
```

### 2️⃣ Acceder al Sistema
```
URL: http://localhost/Proyecto_PHP/
```
➡️ El sistema te redirigirá automáticamente a `login.php`

---

## 🔑 Credenciales de Prueba

### 👨‍💼 Administrador (Acceso Total)
```
Usuario:    admin
Contraseña: admin
```
**Puede acceder a:**
- ✅ Nuevo Producto
- ✅ Editar Producto  
- ✅ Nuevo Cliente
- ✅ Editar Cliente
- ✅ Generar Venta
- ✅ Reporte de Ventas
- ✅ Reporte Stock Mínimo

### 🛒 Vendedor (Acceso Limitado)
```
Usuario:    vendedor1
Contraseña: vendedor
```
**Puede acceder a:**
- ✅ Nuevo Producto
- ✅ Nuevo Cliente
- ✅ Generar Venta
- ❌ NO puede editar productos/clientes
- ❌ NO puede ver reportes

---

## 🧪 Casos de Prueba

### ✅ Test 1: Login como Admin
1. Abrir: `http://localhost/Proyecto_PHP/login.php`
2. Ingresar: `admin` / `admin`
3. Click en "Iniciar Sesión"
4. **Resultado Esperado:**
   - Redirige a `index.php`
   - Navbar muestra: "Administrador Sistema"
   - Dashboard muestra 7 tarjetas de módulos
   - Badge verde indica: "Rol: Admin"

### ✅ Test 2: Login como Vendedor
1. Cerrar sesión (Click en usuario → Cerrar Sesión)
2. Ingresar: `vendedor1` / `vendedor`
3. Click en "Iniciar Sesión"
4. **Resultado Esperado:**
   - Redirige a `index.php`
   - Navbar muestra: "Carlos Vendedor"
   - Dashboard muestra solo 3 tarjetas
   - Badge azul indica: "Rol: Vendedor"
   - Mensaje informativo sobre permisos limitados

### ✅ Test 3: Acceso Denegado
1. Login como vendedor
2. En la barra de navegación NO aparece "Editar Producto"
3. Intentar acceder directamente:
   ```
   http://localhost/Proyecto_PHP/views/productos/editar.php
   ```
4. **Resultado Esperado:**
   - Redirige a `auth/sin_permiso.php`
   - Muestra mensaje: "Acceso Denegado"
   - Indica rol actual: "Vendedor"
   - Botones para volver al inicio o cerrar sesión

### ✅ Test 4: Acceso Sin Login
1. Cerrar sesión completamente
2. Intentar acceder directamente:
   ```
   http://localhost/Proyecto_PHP/index.php
   ```
3. **Resultado Esperado:**
   - Redirige automáticamente a `auth/login.php`
   - No puede acceder sin autenticarse

### ✅ Test 5: Navegación con Permisos
1. Login como vendedor
2. Verificar que en el navbar SOLO aparecen:
   - 🏠 Inicio
   - ➕ Nuevo Producto
   - 👤 Nuevo Cliente
   - 📊 Reportes → Generar Venta
   - 👤 Carlos Vendedor → Cerrar Sesión
3. **NO debe aparecer:**
   - ❌ Editar Producto
   - ❌ Editar Cliente
   - ❌ Reporte de Ventas
   - ❌ Reporte Stock Mínimo

### ✅ Test 6: Crear Producto como Vendedor
1. Login como vendedor
2. Click en "Nuevo Producto"
3. **Resultado Esperado:**
   - ✅ Acceso permitido
   - Puede crear productos normalmente

### ✅ Test 7: Generar Venta como Vendedor
1. Login como vendedor
2. Click en "Generar Venta"
3. **Resultado Esperado:**
   - ✅ Acceso permitido
   - Puede generar ventas normalmente

---

## 🎯 Resumen de Pruebas

| Test | Descripción | Estado |
|------|-------------|--------|
| 1 | Login Admin | ⬜ Por probar |
| 2 | Login Vendedor | ⬜ Por probar |
| 3 | Acceso Denegado | ⬜ Por probar |
| 4 | Sin Login | ⬜ Por probar |
| 5 | Navegación Permisos | ⬜ Por probar |
| 6 | Crear Producto Vendedor | ⬜ Por probar |
| 7 | Generar Venta Vendedor | ⬜ Por probar |

---

## 🐛 Solución de Problemas

### Problema: "No se puede iniciar sesión"
**Solución:**
- Verificar que XAMPP Apache y MySQL estén activos
- Verificar que la base de datos `inventario_tienda` exista
- Verificar que la tabla `usuarios` tenga datos

### Problema: "Redirige a login constantemente"
**Solución:**
- Verificar que las sesiones PHP estén habilitadas
- En `php.ini` verificar: `session.save_path` esté configurado
- Reiniciar Apache en XAMPP

### Problema: "Error 404 en login.php"
**Solución:**
- Verificar la ruta correcta: `http://localhost/Proyecto_PHP/auth/login.php`
- Verificar que el proyecto esté en `C:\xampp\htdocs\Proyecto_PHP`
- Verificar que exista la carpeta `auth/` con los 3 archivos

### Problema: "Página en blanco"
**Solución:**
- Activar errores PHP para ver mensajes:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- Revisar logs de Apache: `C:\xampp\apache\logs\error.log`

---

## 📸 Capturas Esperadas

### Login Page
```
╔════════════════════════════════════╗
║     🏪 Sistema de Inventario       ║
║        Tienda de Ropa              ║
╠════════════════════════════════════╣
║   Iniciar Sesión                   ║
║                                    ║
║   Usuario: [______________]        ║
║   Contraseña: [______________]     ║
║                                    ║
║   [  Iniciar Sesión  ]             ║
║                                    ║
║   ℹ️ Credenciales de prueba:       ║
║   Admin: admin / admin             ║
║   Vendedor: vendedor1 / vendedor   ║
╚════════════════════════════════════╝
```

### Dashboard Admin (7 Tarjetas)
```
═══════════════════════════════════════════════════════
Inventario Tienda de Ropa    [Administrador Sistema ▼]
═══════════════════════════════════════════════════════

Administrador Sistema | Rol: Admin
───────────────────────────────────────────────────────

[Nuevo       [Nuevo      [Editar      [Editar
Producto]    Cliente]     Producto]    Cliente]

[Generar     [Reporte     [Reporte
Venta]       Ventas]      Stock Mín]
```

### Dashboard Vendedor (3 Tarjetas)
```
═══════════════════════════════════════════════════════
Inventario Tienda de Ropa    [Carlos Vendedor ▼]
═══════════════════════════════════════════════════════

Carlos Vendedor | Rol: Vendedor
───────────────────────────────────────────────────────

[Nuevo       [Nuevo      [Generar
Producto]    Cliente]     Venta]

ℹ️ Como Vendedor, tienes acceso a: Crear productos,
   Crear clientes y Generar ventas.
```

---

## ✅ Checklist de Verificación

Antes de dar por terminado, verificar:

- [ ] Los archivos fueron creados correctamente
- [ ] XAMPP está corriendo (Apache + MySQL)
- [ ] La base de datos tiene los usuarios precargados
- [ ] Login funciona con ambos usuarios
- [ ] Admin ve todos los módulos
- [ ] Vendedor ve solo 3 módulos
- [ ] Acceso denegado funciona correctamente
- [ ] Cierre de sesión funciona
- [ ] Redirección a login funciona sin sesión

---

## 🎉 Sistema Listo!

Si todos los tests pasan, el sistema de autenticación está **100% funcional** ✨

**Próximos pasos sugeridos:**
1. Probar crear productos con ambos usuarios
2. Probar generar ventas con vendedor
3. Verificar que vendedor NO puede editar
4. Verificar que admin puede hacer todo

---

**¿Listo para probar?** 🚀

Abre tu navegador y ve a: `http://localhost/Proyecto_PHP/`

