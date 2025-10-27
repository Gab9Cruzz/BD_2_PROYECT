# ⚡ INSTALACIÓN RÁPIDA - 5 MINUTOS
## Sistema de Inventario para Tienda de Ropa

---

## 📋 CHECKLIST DE INSTALACIÓN

Sigue estos pasos en orden. Marca cada uno al completarlo:

- [ ] **Paso 1**: Instalar XAMPP
- [ ] **Paso 2**: Copiar proyecto a htdocs
- [ ] **Paso 3**: Iniciar Apache y MySQL
- [ ] **Paso 4**: Crear base de datos en phpMyAdmin
- [ ] **Paso 5**: Importar archivo SQL
- [ ] **Paso 6**: Verificar que funciona

---

## 🔧 PASO 1: INSTALAR XAMPP

1. Descarga XAMPP desde: **https://www.apachefriends.org/**
2. Ejecuta el instalador
3. Selecciona componentes:
   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin
4. Instala en la ubicación predeterminada: `C:\xampp`
5. Completa la instalación

**⏱️ Tiempo estimado**: 2-3 minutos

---

## 📁 PASO 2: COPIAR PROYECTO

1. Abre el explorador de archivos
2. Navega a: `C:\xampp\htdocs\`
3. Copia **toda la carpeta** `Proyecto_PHP` aquí

**Resultado esperado**:
```
C:\xampp\htdocs\Proyecto_PHP\
├── index.php
├── config\
├── models\
├── views\
├── public\
├── sql\
└── (otros archivos)
```

**⏱️ Tiempo estimado**: 30 segundos

---

## ▶️ PASO 3: INICIAR SERVICIOS

1. Busca en Windows: "XAMPP Control Panel"
2. Abre **XAMPP Control Panel**
3. Haz clic en **[Start]** junto a **Apache**
4. Haz clic en **[Start]** junto a **MySQL**
5. Espera a que ambos estén en **verde** (Running)

**Visual**:
```
Module    | Status  | Action
----------|---------|--------
Apache    | Running | [Stop]
MySQL     | Running | [Stop]
```

**⏱️ Tiempo estimado**: 30 segundos

---

## 🗄️ PASO 4: CREAR BASE DE DATOS

1. Abre tu navegador (Chrome, Firefox, Edge)
2. Ve a: **`http://localhost/phpmyadmin`**
3. Haz clic en **"Nuevo"** (panel izquierdo)
4. En "Nombre de la base de datos": escribe **`inventario_tienda`**
5. En "Cotejamiento": selecciona **`utf8mb4_unicode_ci`**
6. Haz clic en **"Crear"**

**Resultado esperado**:
- La base de datos `inventario_tienda` aparece en el panel izquierdo
- Está vacía (0 tablas)

**⏱️ Tiempo estimado**: 30 segundos

---

## 📥 PASO 5: IMPORTAR ARCHIVO SQL

1. En phpMyAdmin, **selecciona** la base de datos `inventario_tienda` (clic en el nombre)
2. Ve a la pestaña **"Importar"** (arriba)
3. Haz clic en **"Seleccionar archivo"**
4. Navega a: `C:\xampp\htdocs\Proyecto_PHP\sql\inventario_tienda.sql`
5. Selecciónalo y haz clic en **"Abrir"**
6. Desplázate hacia abajo
7. Haz clic en **"Continuar"**
8. Espera el mensaje de éxito: **✅ "Importación finalizada correctamente"**

**Resultado esperado**:
- Mensaje verde de éxito
- Ahora deberías ver **8 tablas** en el panel izquierdo:
  - Categoria
  - Cliente
  - DetalleVenta
  - FacturaVenta
  - MovimientoInventario
  - Producto
  - Proveedor
  - Telefono_Cliente

**⏱️ Tiempo estimado**: 1 minuto

---

## ✅ PASO 6: VERIFICAR QUE FUNCIONA

### Verificación 1: Página Principal
1. Abre tu navegador
2. Ve a: **`http://localhost/Proyecto_PHP/index.php`**
3. Deberías ver:
   - Navbar azul arriba
   - Título: "Sistema de Inventario"
   - 6 tarjetas con las pantallas del sistema

**✅ Si ves esto → El sistema está instalado correctamente**

### Verificación 2: Probar una Pantalla
1. Haz clic en **"Nuevo Producto"**
2. Deberías ver un formulario con campos:
   - Nombre
   - Talla
   - Color
   - Precio
   - Stock
   - Stock Mínimo
   - Categoría (lista desplegable con opciones)
   - Proveedor (lista desplegable con opciones)

**✅ Si ves las listas desplegables con opciones → La conexión a la BD funciona**

### Verificación 3: Probar un Reporte
1. En el menú, haz clic en **"Reportes"** → **"Stock Bajo Mínimo"**
2. Deberías ver una tabla con productos
3. Columnas: Nombre, Talla, Color, Stock, Mínimo, Estado, Categoría, Proveedor, Teléfono

**✅ Si ves datos → Las vistas SQL funcionan correctamente**

**⏱️ Tiempo estimado**: 1 minuto

---

## 🎉 ¡INSTALACIÓN COMPLETA!

Si llegaste aquí, tu sistema está **100% funcional** y listo para:
- ✅ Crear productos y clientes (2 pantallas de ingreso)
- ✅ Editar productos y clientes (2 pantallas de actualización)
- ✅ Ver reportes de stock y ventas (2 pantallas de reportes)
- ✅ Demostrar elementos avanzados de SQL (vistas, procedures, transacciones)

**NOTA**: Este sistema implementa exactamente 6 pantallas (2+2+2). No incluye pantallas de registro de ventas ya que esa funcionalidad está implementada en el backend mediante el modelo `FacturaVenta.php` con transacciones SQL.

---

## 🆘 PROBLEMAS COMUNES

### ❌ "Connection failed" al abrir el sistema

**Causa**: MySQL no está corriendo

**Solución**:
1. Abre XAMPP Control Panel
2. Verifica que MySQL esté en verde (Running)
3. Si no, haz clic en "Start"

---

### ❌ "Table doesn't exist"

**Causa**: No se importó el archivo SQL

**Solución**:
1. Ve a phpMyAdmin: `http://localhost/phpmyadmin`
2. Haz clic en `inventario_tienda`
3. Si no hay tablas, repite el **Paso 5**

---

### ❌ Apache no inicia (botón "Start" no funciona)

**Causa**: Puerto 80 ocupado por otro programa (Skype, IIS)

**Solución rápida**:
1. Cierra Skype si está abierto
2. O cambia el puerto de Apache:
   - XAMPP Panel → Config (Apache) → httpd.conf
   - Busca: `Listen 80`
   - Cambia a: `Listen 8080`
   - Guarda y reinicia Apache
   - Ahora usa: `http://localhost:8080/Proyecto_PHP/index.php`

---

### ❌ Listas desplegables vacías (Categoría/Proveedor)

**Causa**: El archivo SQL no se importó completamente

**Solución**:
1. Ve a phpMyAdmin
2. Haz clic en tabla `Categoria`
3. Pestaña "Examinar"
4. Si no hay datos (0 filas), importa de nuevo el SQL completo

---

## 📞 AYUDA ADICIONAL

### URLs Importantes

- **Sistema**: `http://localhost/Proyecto_PHP/index.php`
- **phpMyAdmin**: `http://localhost/phpmyadmin`

### Archivos de Configuración

- **Conexión BD**: `config/conexion.php`
- **SQL Completo**: `sql/inventario_tienda.sql`

### Credenciales por Defecto

- **Usuario MySQL**: `root`
- **Contraseña MySQL**: (vacío - sin contraseña)
- **Puerto Apache**: `80` (o `8080` si lo cambiaste)
- **Puerto MySQL**: `3306`

---

## 📚 SIGUIENTE PASO

Una vez instalado, revisa:
- **`DOCUMENTACION_SQL.md`**: Explicación detallada de cada tabla, vista y procedure
- **`CASOS_DE_PRUEBA.md`**: Pruebas para verificar todas las funcionalidades
- **`GUIA_PRESENTACION.md`**: Cómo presentar el proyecto

---

**¡Listo para usar! 🚀**

### 1️⃣ INSTALAR XAMPP
- Descarga desde: https://www.apachefriends.org/
- Instala XAMPP en tu PC

### 2️⃣ COPIAR PROYECTO
- Copia esta carpeta completa a: `C:\xampp\htdocs\Proyecto_PHP`

### 3️⃣ INICIAR SERVICIOS
- Abre XAMPP Control Panel
- Click en "Start" en Apache
- Click en "Start" en MySQL

### 4️⃣ CREAR BASE DE DATOS
- Abre tu navegador
- Ve a: http://localhost/phpmyadmin
- Click en "Nuevo"
- Nombre: `inventario_tienda`
- Click en "Crear"

### 5️⃣ IMPORTAR TABLAS
- Selecciona la base de datos `inventario_tienda`
- Click en "Importar"
- Click en "Seleccionar archivo"
- Busca: `sql/inventario_tienda.sql`
- Click en "Continuar"

### 6️⃣ ACCEDER AL SISTEMA
- Abre tu navegador
- Ve a: http://localhost/Proyecto_PHP/index.php
- ¡Listo! 🎉

---

## 📋 FUNCIONALIDADES DISPONIBLES

### Productos
- ✅ Ver lista de productos
- ✅ Crear nuevo producto
- ✅ Editar producto
- ✅ Eliminar producto
- ✅ Registrar entrada de stock

### Clientes
- ✅ Ver lista de clientes
- ✅ Crear nuevo cliente
- ✅ Editar cliente
- ✅ Gestionar teléfonos del cliente

### Ventas
- ✅ Registrar nueva venta
- ✅ Ver historial de ventas
- ✅ Imprimir factura

### Reportes
- ✅ Productos con stock bajo
- ✅ Ventas por rango de fechas

---

## 🔐 CREDENCIALES DE BASE DE DATOS

**Host:** localhost  
**Usuario:** root  
**Contraseña:** (vacío)  
**Base de Datos:** inventario_tienda  

Si necesitas cambiar estas credenciales, edita:
`config/conexion.php`

---

## ⚠️ PROBLEMAS COMUNES

**Error de conexión:**
- Verifica que Apache y MySQL estén iniciados en XAMPP
- Comprueba que la base de datos exista

**No aparecen las páginas:**
- Asegúrate de que la carpeta esté en `C:\xampp\htdocs\`
- Verifica la URL: http://localhost/Proyecto_PHP/index.php

**Error en las ventas:**
- Primero crea categorías y productos
- Verifica que haya stock disponible

---

## 📊 DATOS DE PRUEBA

El sistema incluye datos de ejemplo:
- 5 Categorías (Camisetas, Pantalones, Vestidos, Accesorios, Calzado)
- 3 Proveedores
- 6 Productos de ejemplo
- 3 Clientes con teléfonos

¡Puedes hacer ventas de prueba inmediatamente!

---

## 💡 TIPS

1. **Antes de vender**, verifica que haya stock disponible
2. **El sistema previene** stock negativo automáticamente
3. **Las ventas usan transacciones** - si algo falla, se revierte todo
4. **Puedes imprimir** las facturas y reportes
5. **El reporte de stock mínimo** te avisa qué productos reponer

---

**¡Disfruta del sistema! 🎉**
