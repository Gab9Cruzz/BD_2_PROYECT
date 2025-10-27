# 🛍️ Sistema de Inventario - Tienda de Ropa
## 🇪🇨 Configurado para Ecuador - Precios en USD ($)

## 📋 Instrucciones de Configuración

### ✅ Opción 1: Base de datos nueva (RECOMENDADO)

1. **Eliminar base de datos anterior (si existe)**
   ```sql
   DROP DATABASE IF EXISTS inventario_tienda;
   ```

2. **Importar el script completo**
   - Abrir phpMyAdmin
   - Ir a la pestaña "SQL"
   - Copiar y ejecutar todo el contenido de: `sql/inventario_tienda.sql`

### ✅ Opción 2: Base de datos existente (solo actualizar)

1. **Ejecutar script de actualización**
   - Abrir phpMyAdmin
   - Seleccionar la base de datos `inventario_tienda`
   - Ir a la pestaña "SQL"
   - Copiar y ejecutar: `sql/actualizar_precios_ecuador.sql`

---

## 💵 Configuración Ecuador

### Moneda: **Dólares USD ($)**
- Todos los precios están en dólares
- Formato: `$ 15.99`

### Métodos de Pago Disponibles:
- ✅ Efectivo
- ✅ Tarjeta Débito
- ✅ Tarjeta Crédito
- ✅ Transferencia Bancaria
- ✅ Depósito Bancario

---

## 🎯 6 Pantallas del Sistema

### 📥 INGRESO DE DATOS (2 pantallas)
1. **Nuevo Producto** - `/views/productos/crear.php`
2. **Nuevo Cliente** - `/views/clientes/crear.php`

### ✏️ ACTUALIZACIÓN DE DATOS (2 pantallas)
3. **Editar Producto** - `/views/productos/editar.php`
4. **Editar Cliente** - `/views/clientes/editar.php`

### 📊 REPORTES DE DATOS (2 pantallas)
5. **Generar Venta** - `/views/ventas/generar.php` ⭐ NUEVA
6. **Reporte de Ventas** - `/views/reportes/ventas.php`

---

## 🔧 Características Implementadas

### ✅ Elementos Avanzados SQL

1. **2 VISTAS**
   - ✅ `vista_stock_bajo` - Productos con stock bajo
   - ✅ `vista_ventas_completas` - Ventas con detalles

2. **1 STORED PROCEDURE**
   - ✅ `sp_reporte_ventas_fechas` - Usado en `/views/reportes/ventas.php`

3. **TRANSACCIONES**
   - ✅ Implementadas en `FacturaVenta::crearVenta()`
   - Garantiza integridad: Factura + Detalles + Actualización Stock

### ✅ Funcionalidades Principales

- 🛒 **Generar Ventas** con múltiples productos
- 📉 **Actualización automática de stock**
- 💰 **Cálculo automático de totales**
- 📊 **Reportes con filtros de fecha**
- 🔒 **Validación de stock disponible**

---

## 🚀 Cómo Usar el Sistema

### 1️⃣ Generar una Venta
1. Ir a **"Generar Venta"**
2. Seleccionar cliente (opcional)
3. Elegir método de pago
4. Agregar productos
5. Click en **"Generar Venta"**
6. ✅ El stock se actualiza automáticamente

### 2️⃣ Ver Reporte de Ventas
1. Ir a **"Reporte de Ventas"**
2. Seleccionar rango de fechas
3. Click en **"Generar Reporte"**
4. 📊 Se usa el **Stored Procedure** `sp_reporte_ventas_fechas`

---

## 📝 Notas Importantes

- ✅ Todos los errores 404 están corregidos
- ✅ Error de sintaxis en `ventas.php` corregido
- ✅ El campo **precio** se agregó a la tabla Producto
- ✅ El sistema valida stock antes de realizar ventas
- ✅ Las transacciones garantizan consistencia de datos

---

## 🎨 Tecnologías Usadas

- PHP 8.2
- MySQL 8.0
- Bootstrap 5.3
- PDO (PHP Data Objects)
- Transacciones SQL
- Stored Procedures

---

**Desarrollado para demostrar:**
- ✅ Normalización 3FN
- ✅ Vistas SQL
- ✅ Stored Procedures
- ✅ Transacciones
- ✅ CRUD completo
- ✅ Sistema funcional de ventas
