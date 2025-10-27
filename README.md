# 🛍️ Sistema de Inventario para Tienda de Ropa

Sistema web desarrollado en PHP puro y MySQL para gestionar el inventario de una tienda de ropa. Cumple con el requerimiento académico de **2 pantallas de ingreso + 2 de actualización + 2 de reportes**.

## 📋 Características Principales

- ✅ **2 Pantallas de Ingreso**: Crear productos y clientes
- ✅ **2 Pantallas de Actualización**: Editar productos y clientes
- ✅ **2 Pantallas de Reportes**: Stock bajo mínimo y ventas por fechas
- ✅ Base de datos normalizada (3FN) con 8 tablas
- ✅ **Vistas SQL (2)**: Consultas complejas simplificadas
- ✅ **Stored Procedures (1)**: Reporte de ventas optimizado
- ✅ **Transacciones SQL**: Control ACID en operaciones críticas
- ✅ Seguridad con PDO y Prepared Statements
- ✅ Interfaz responsive con Bootstrap 5
- ✅ Gestión de múltiples teléfonos por cliente

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 8.2 (puro, sin frameworks)
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Servidor Local**: XAMPP
- **Arquitectura**: MVC simplificado
- **Conexión DB**: PDO con Prepared Statements

## 📁 Estructura del Proyecto

```
Proyecto_PHP/
├── config/
│   └── conexion.php          # Conexión PDO a la base de datos
├── models/
│   ├── Producto.php          # Modelo de productos
│   ├── Cliente.php           # Modelo de clientes
│   ├── FacturaVenta.php      # Modelo de ventas
│   ├── MovimientoInventario.php
│   ├── Categoria.php
│   └── Proveedor.php
├── views/
│   ├── productos/
│   │   ├── crear.php         # ✅ INGRESO: Crear producto
│   │   └── editar.php        # ✅ ACTUALIZACIÓN: Editar producto
│   ├── clientes/
│   │   ├── crear.php         # ✅ INGRESO: Crear cliente
│   │   ├── editar.php        # ✅ ACTUALIZACIÓN: Editar cliente
│   │   ├── agregar_telefono.php
│   │   └── eliminar_telefono.php
│   └── reportes/
│       ├── stock_minimo.php  # ✅ REPORTE: Stock bajo mínimo
│       └── ventas.php        # ✅ REPORTE: Ventas por fechas
├── public/
│   └── css/
│       └── style.css
├── sql/
│   └── inventario_tienda.sql
└── index.php                  # Página principal con navegación
```

## 🎯 Las 6 Pantallas Obligatorias

### 📥 Pantallas de Ingreso (2)
1. **Crear Producto** (`views/productos/crear.php`)
   - Formulario para registrar nuevos productos
   - Selección de categoría y proveedor
   - Definición de stock inicial y mínimo

2. **Crear Cliente** (`views/clientes/crear.php`)
   - Formulario para registrar nuevos clientes
   - Soporte para múltiples teléfonos
   - Campos: nombre, dirección, correo

### ✏️ Pantallas de Actualización (2)
3. **Editar Producto** (`views/productos/editar.php`)
   - Selector integrado de productos (no requiere listado previo)
   - Actualización de datos del producto
   - Modificación de categoría, proveedor y stock

4. **Editar Cliente** (`views/clientes/editar.php`)
   - Selector integrado de clientes (no requiere listado previo)
   - Actualización de datos del cliente
   - Gestión de teléfonos (agregar/eliminar)

### 📊 Pantallas de Reportes (2)
5. **Reporte de Stock Bajo Mínimo** (`views/reportes/stock_minimo.php`)
   - Lista productos con stock por debajo del mínimo
   - Indicadores visuales de nivel crítico
   - Información de proveedor para reposición

6. **Reporte de Ventas por Fechas** (`views/reportes/ventas.php`)
   - Filtrado por rango de fechas
   - Total de ventas y promedio
   - Detalles de cada factura

## 🚀 Instalación y Puesta en Marcha

### ✅ Requisitos Previos

- **XAMPP** instalado (PHP 8.2 + MySQL)
- **Navegador web** moderno (Chrome, Firefox, Edge)

### 📦 Pasos de Instalación (5 minutos)

#### 1️⃣ Instalar XAMPP
   - Descarga XAMPP desde [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Instala XAMPP en tu sistema (ubicación predeterminada: `C:\xampp`)
   - Asegúrate de instalar **Apache** y **MySQL**

#### 2️⃣ Copiar el Proyecto
   ```
   Copia toda la carpeta del proyecto a:
   C:\xampp\htdocs\Proyecto_PHP
   
   La estructura debe quedar así:
   C:\xampp\htdocs\Proyecto_PHP\index.php
   C:\xampp\htdocs\Proyecto_PHP\config\
   C:\xampp\htdocs\Proyecto_PHP\models\
   C:\xampp\htdocs\Proyecto_PHP\views\
   C:\xampp\htdocs\Proyecto_PHP\sql\
   ...
   ```

#### 3️⃣ Iniciar Servicios de XAMPP
   - Abre **XAMPP Control Panel**
   - Haz clic en **Start** junto a **Apache**
   - Haz clic en **Start** junto a **MySQL**
   - Espera a que ambos servicios estén en verde (Running)

#### 4️⃣ Crear la Base de Datos
   - Abre tu navegador
   - Ve a: `http://localhost/phpmyadmin`
   - Haz clic en **"Nuevo"** en el panel izquierdo
   - En "Nombre de la base de datos": escribe `inventario_tienda`
   - En "Cotejamiento": selecciona `utf8mb4_unicode_ci`
   - Haz clic en **"Crear"**

#### 5️⃣ Importar el Archivo SQL
   - En phpMyAdmin, selecciona la base de datos **`inventario_tienda`** (panel izquierdo)
   - Ve a la pestaña **"Importar"**
   - Haz clic en **"Seleccionar archivo"**
   - Navega hasta: `C:\xampp\htdocs\Proyecto_PHP\sql\inventario_tienda.sql`
   - Selecciónalo y haz clic en **"Abrir"**
   - Desplázate hacia abajo y haz clic en **"Continuar"**
   - Espera el mensaje de éxito: ✅ "Importación finalizada correctamente"

#### 6️⃣ Verificar la Importación
   - En phpMyAdmin, haz clic en la base de datos `inventario_tienda`
   - Deberías ver **8 tablas**:
     - Categoria
     - Cliente
     - DetalleVenta
     - FacturaVenta
     - MovimientoInventario
     - Producto
     - Proveedor
     - Telefono_Cliente
   - También deberías ver **2 vistas** y **1 procedimiento**
   - Haz clic en alguna tabla (ej: Producto) → pestaña "Examinar" para ver datos de ejemplo

#### 7️⃣ Acceder al Sistema
   - Abre tu navegador
   - Ve a: **`http://localhost/Proyecto_PHP/index.php`**
   - Deberías ver la página principal con 6 tarjetas (las 6 pantallas del sistema)

### 🎯 Verificación Rápida

Para confirmar que todo funciona:

1. **Página principal carga**: `http://localhost/Proyecto_PHP/index.php` ✅
2. **Crear producto funciona**: Clic en "Nuevo Producto" → llenar formulario → Guardar ✅
3. **Reporte stock mínimo**: Clic en "Stock Bajo Mínimo" → debe mostrar productos ✅
4. **Reporte ventas**: Clic en "Reporte de Ventas" → seleccionar fechas → debe mostrar datos ✅

### 🔧 Solución de Problemas Comunes

#### ❌ Error: "Connection failed"
**Causa**: MySQL no está corriendo o credenciales incorrectas

**Solución**:
1. Abre XAMPP Control Panel
2. Verifica que MySQL esté en **verde** (Running)
3. Si no, haz clic en "Start" junto a MySQL
4. Si el problema persiste, verifica `config/conexion.php`:
   ```php
   private $username = "root";
   private $password = "";  // Dejar vacío en XAMPP por defecto
   ```

#### ❌ Error: "Table doesn't exist"
**Causa**: El archivo SQL no se importó correctamente

**Solución**:
1. Ve a phpMyAdmin: `http://localhost/phpmyadmin`
2. Selecciona base de datos `inventario_tienda`
3. Si no tiene tablas, importa de nuevo el archivo `sql/inventario_tienda.sql`
4. Asegúrate de que el archivo se importó completamente (busca mensaje de éxito)

#### ❌ Error: "Cannot modify header information"
**Causa**: Espacios o BOM antes de `<?php`

**Solución**:
- Guarda todos los archivos PHP con encoding **UTF-8 sin BOM**
- Asegúrate de que no haya espacios antes de `<?php`

#### ❌ Página en blanco o error 500
**Causa**: Error de PHP no mostrado

**Solución**:
1. Abre `config/conexion.php`
2. Agrega al inicio (después de `<?php`):
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
3. Recarga la página y verás el error específico

#### ❌ Apache no inicia (Puerto 80 ocupado)
**Causa**: Skype u otro programa usa el puerto 80

**Solución**:
1. En XAMPP Control Panel, haz clic en "Config" junto a Apache
2. Selecciona "httpd.conf"
3. Busca: `Listen 80`
4. Cámbialo a: `Listen 8080`
5. Guarda y reinicia Apache
6. Ahora accede con: `http://localhost:8080/Proyecto_PHP/index.php`

## 📊 Base de Datos

### Elementos Avanzados Implementados

- **2 Vistas SQL**: 
  - `vista_stock_bajo`: Usada en reporte de stock mínimo
  - `vista_ventas_completas`: Disponible para consultas
  
- **1 Stored Procedure**: 
  - `sp_reporte_ventas_fechas`: Usado en reporte de ventas por fechas
  
- **Transacciones**: Implementadas en modelo `FacturaVenta.php`
  - Control ACID (Atomicity, Consistency, Isolation, Durability)
  - Rollback automático en caso de errores

**Principio aplicado**: Calidad sobre cantidad - TODO lo implementado se USA en el sistema.

### Diagrama E-R

El sistema utiliza las siguientes tablas:

- **Proveedor**: Información de proveedores
- **Categoria**: Categorías de productos
- **Producto**: Productos del inventario
- **Cliente**: Información de clientes
- **Telefono_Cliente**: Teléfonos de clientes (relación 1:N)
- **FacturaVenta**: Facturas de ventas
- **DetalleVenta**: Detalle de productos vendidos
- **MovimientoInventario**: Registro de entradas/salidas de stock

### Normalización

- Cumple con la 3ra Forma Normal (3FN)
- Uso de claves foráneas con integridad referencial
- Restricciones CHECK para prevenir stock negativo
- Índices para optimizar consultas

## 🔧 Funcionalidades Principales

### 1. Gestión de Productos (Ingreso y Actualización)
- ✅ Crear nuevos productos con categoría y proveedor
- ✅ Editar productos existentes con selector integrado
- ✅ Control de stock mínimo y actual
- ✅ Prevención de stock negativo mediante restricciones DB

### 2. Gestión de Clientes (Ingreso y Actualización)
- ✅ Crear nuevos clientes con datos completos
- ✅ Editar clientes existentes con selector integrado
- ✅ Gestionar múltiples teléfonos por cliente
- ✅ Validación de correos electrónicos

### 3. Sistema de Reportes
- ✅ **Stock Bajo Mínimo** (usa VISTA `vista_stock_bajo`):
  - Identifica productos que necesitan reposición
  - Indicadores visuales (Sin Stock, Crítico, En Mínimo)
  - Información de proveedor para contacto
  - Ordenado por nivel de urgencia
  - **Campo calculado**: `estado_stock` se genera automáticamente en la BD
  
- ✅ **Ventas por Fechas** (usa STORED PROCEDURE `sp_reporte_ventas_fechas`):
  - Filtrado por rango de fechas personalizado
  - Retorna 2 conjuntos de datos:
    1. Lista de ventas con detalles de cliente
    2. Estadísticas calculadas (total, promedio, mín/máx)
  - Más eficiente que cálculos en PHP
  - Detalles de método de pago y cantidad de items

### 4. Integridad de Datos (Backend)
- ✅ Transacciones SQL implementadas en `models/FacturaVenta.php` (BEGIN/COMMIT/ROLLBACK)
- ✅ Modelo preparado para registro de ventas con control ACID
- ✅ Actualización de inventario mediante MovimientoInventario
- ✅ Restricciones de integridad referencial con claves foráneas

## 🔒 Seguridad

- Uso de PDO con Prepared Statements (prevención de SQL Injection)
- Sanitización de datos con `htmlspecialchars()`
- Validación de datos en servidor
- Transacciones SQL para operaciones críticas
- Manejo de errores con try/catch

## 🎨 Interfaz de Usuario

- Diseño responsive con Bootstrap 5
- Paleta de colores moderna
- Iconos de Bootstrap Icons
- Efectos hover y animaciones
- Alertas y mensajes de confirmación
- Impresión de documentos

## 📝 Datos de Ejemplo

El script SQL incluye datos de ejemplo:
- 5 categorías de productos
- 3 proveedores
- 6 productos de ejemplo
- 3 clientes con teléfonos

## 🧪 Pruebas Recomendadas

### Pruebas de Pantallas de Ingreso
1. **Crear un producto** con todos los campos y verificar que se guarde
2. **Crear un cliente** con múltiples teléfonos separados por comas
3. Verificar que las validaciones funcionen (campos obligatorios)

### Pruebas de Pantallas de Actualización
4. **Editar un producto** usando el selector integrado
5. **Editar un cliente** y agregar/eliminar teléfonos
6. Verificar que los cambios se guarden correctamente

### Pruebas de Pantallas de Reportes
7. **Generar reporte de stock bajo mínimo** y verificar indicadores
8. **Generar reporte de ventas** filtrando por rango de fechas
9. Verificar cálculos de totales y promedios

### Pruebas de Integridad
10. Intentar crear producto sin categoría (debe fallar)
11. Verificar que los datos se muestren correctamente en los selectores

## 📖 Configuración de Base de Datos

Si necesitas cambiar las credenciales de la base de datos, edita el archivo:
`config/conexion.php`

```php
private $host = "localhost";
private $db_name = "inventario_tienda";
private $username = "root";
private $password = "";
```

## 🐛 Solución de Problemas

### Error: "Connection failed"
- Verifica que MySQL esté iniciado en XAMPP
- Comprueba que la base de datos exista
- Revisa las credenciales en `config/conexion.php`

### Error: "Table doesn't exist"
- Asegúrate de haber importado el archivo SQL
- Verifica el nombre de la base de datos

### Error: "Cannot insert... foreign key constraint fails"
- Verifica que existan categorías antes de crear productos
- Asegúrate de seleccionar un producto válido en las ventas

## 📄 Licencia

Este es un proyecto académico para fines educativos.

## 👨‍💻 Autor

Proyecto desarrollado como parte de un trabajo académico de base de datos.

**Cumplimiento**: Sistema implementa exactamente **2+2+2 pantallas** según requerimiento del profesor.

---

**¡Sistema listo para presentar! 🎉**
