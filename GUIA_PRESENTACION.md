# 🎤 GUÍA PARA PRESENTACIÓN
## Sistema Web de Inventario para Tienda de Ropa
### ✅ Cumplimiento: 2 Ingreso + 2 Actualización + 2 Reportes

---

## 📊 DIAPOSITIVA 1: PORTADA
**Título:** Sistema Web de Inventario para Tienda de Ropa  
**Subtítulo:** Sistema de 6 pantallas (2+2+2)  
**Tecnologías:** PHP | MySQL | Bootstrap  
**Autor:** [Tu nombre]  
**Fecha:** Octubre 2025

---

## 📊 DIAPOSITIVA 2: ÍNDICE
1. Requerimiento del Proyecto (2+2+2)
2. Modelo Entidad-Relación
3. Diseño Relacional (Tablas)
4. Pantallas Implementadas (demostración)
5. Flujo de Transacciones
6. Conclusiones

---

## 📊 DIAPOSITIVA 3: REQUERIMIENTO DEL PROYECTO

### Especificación Académica:
✅ **2 Pantallas de INGRESO** - Crear nuevos registros  
✅ **2 Pantallas de ACTUALIZACIÓN** - Modificar registros  
✅ **2 Pantallas de REPORTES** - Consultar información  

### Implementación:
**INGRESO:**
1. Crear Producto
2. Crear Cliente

**ACTUALIZACIÓN:**
3. Editar Producto
4. Editar Cliente

**REPORTES:**
5. Stock Bajo Mínimo
6. Ventas por Fechas

**Cumplimiento:** ✅ 100% (exactamente 6 pantallas)

---

## 📊 DIAPOSITIVA 4: MODELO E-R (Diagrama)

```
┌─────────────┐         ┌──────────────┐
│  Proveedor  │◄────┐   │   Categoria  │
└─────────────┘     │   └──────────────┘
                    │           ▲
                    │           │
                    ▼           │
              ┌──────────────┐  │
              │   Producto   │◄─┘
              └──────────────┘
                    │
                    ▼
         ┌───────────────────────┐
         │ MovimientoInventario  │
         └───────────────────────┘

┌──────────┐        ┌──────────────┐
│ Cliente  │◄───────│FacturaVenta  │
└──────────┘        └──────────────┘
     │                     │
     ▼                     ▼
┌─────────────┐    ┌──────────────┐
│ Telefono_   │    │ DetalleVenta │
│  Cliente    │    └──────────────┘
└─────────────┘            │
                           ▼
                    ┌──────────────┐
                    │   Producto   │
                    └──────────────┘
```

**Entidades:** 8 tablas  
**Relaciones:** 7 conexiones principales  
**Normalización:** 3ra Forma Normal (3FN)

---

## 📊 DIAPOSITIVA 5: DISEÑO RELACIONAL - TABLAS PRINCIPALES

### Producto
- PK: id_producto
- nombre, talla, color
- stock, stock_minimo ✅ CHECK (stock >= 0)
- FK: id_categoria, id_proveedor

### Cliente
- PK: id_cliente
- nombre, direccion, correo

### FacturaVenta
- PK: id_factura
- fecha, metodo_pago, total
- FK: id_cliente

### DetalleVenta
- PK: id_detalle
- cantidad, precio_unitario, subtotal
- FK: id_factura, id_producto

---

## 📊 DIAPOSITIVA 6: RESTRICCIONES Y SEGURIDAD

### Restricciones de Integridad:
- **ON DELETE CASCADE:** Cliente → Teléfonos
- **ON DELETE RESTRICT:** Producto → Ventas
- **ON DELETE SET NULL:** Cliente → Facturas
- **CHECK:** Stock no negativo
- **UNIQUE:** Nombres de categorías

### Medidas de Seguridad:
- ✅ PDO con Prepared Statements
- ✅ Sanitización de datos (htmlspecialchars)
- ✅ Validaciones en servidor y cliente
- ✅ Transacciones SQL (ACID)

---

## 📊 DIAPOSITIVA 7: PANTALLAS DE INGRESO (2)

### 1. Crear Producto (`productos/crear.php`)
**Demostración en vivo:**
- Mostrar formulario completo
- Seleccionar categoría: "Camisetas"
- Seleccionar proveedor: "Textiles del Norte"
- Ingresar: nombre, talla (M), color (Azul)
- Definir stock inicial: 100
- Definir stock mínimo: 20
- Guardar y verificar mensaje de éxito

### 2. Crear Cliente (`clientes/crear.php`)
**Demostración en vivo:**
- Mostrar formulario
- Ingresar nombre: "Pedro Martínez"
- Ingresar dirección: "Av. Central 555"
- Ingresar correo: "pedro@email.com"
- Ingresar teléfonos: "999-111-222, 999-333-444"
- Guardar y verificar que se creó con múltiples teléfonos

---

## 📊 DIAPOSITIVA 8: PANTALLAS DE ACTUALIZACIÓN (2)

### 3. Editar Producto (`productos/editar.php`)
**Demostración en vivo:**
- Mostrar selector integrado de productos
- Buscar producto por ID o seleccionar de la tabla
- Mostrar formulario pre-llenado
- Modificar stock de 100 a 80
- Actualizar y verificar cambios

### 4. Editar Cliente (`clientes/editar.php`)
**Demostración en vivo:**
- Mostrar selector integrado de clientes
- Buscar cliente por ID o seleccionar de la tabla
- Modificar dirección
- Agregar un nuevo teléfono
- Eliminar un teléfono existente
- Guardar cambios

---

## 📊 DIAPOSITIVA 9: PANTALLAS DE REPORTES (2)

### 5. Reporte Stock Bajo Mínimo (`reportes/stock_minimo.php`)
**Demostración en vivo:**
- Acceder al reporte
- Mostrar productos con alertas:
  - 🔴 Sin Stock (stock = 0)
  - 🟠 Crítico (stock < mínimo)
  - 🟡 En Mínimo (stock = mínimo)
- Explicar que incluye info de proveedor para reposición
- Destacar ordenamiento por urgencia

### 6. Reporte Ventas por Fechas (`reportes/ventas.php`)
**Demostración en vivo:**
- Ingresar rango de fechas (ejemplo: última semana)
- Generar reporte
- Mostrar:
  - Lista de facturas con cliente y total
  - Total general de ventas del período
  - Promedio por venta
  - Métodos de pago utilizados

---

## 📊 DIAPOSITIVA 11: TRANSACCIONES SQL (BACKEND)

### Implementación en FacturaVenta.php

Las transacciones SQL están implementadas en el **modelo PHP** (no en pantallas visibles).

```php
// Método: crearVenta()
try {
    $this->conn->beginTransaction();
    
    // 1. Insertar factura
    // 2. Insertar detalles de venta
    // 3. Actualizar stock de productos
    // 4. Registrar movimientos de inventario
    
    $this->conn->commit();  // Todo OK
    
} catch(Exception $e) {
    $this->conn->rollBack(); // Revertir si falla
}
```

**Ventaja:** Garantiza integridad de datos (propiedades ACID).

---

## 📊 DIAPOSITIVA 12: TRANSACCIONES - EJEMPLO PRÁCTICO

### Escenario: Venta de 2 Camisetas + 1 Pantalón

#### Sin Transacción (❌):
1. Inserta factura ✅
2. Agrega detalle camisetas ✅
3. Actualiza stock camisetas ✅
4. **Error en pantalón** ❌
5. **Resultado: Datos inconsistentes** 🚫
   - Factura creada pero incompleta
   - Stock actualizado parcialmente

#### Con Transacción (✅):
1. BEGIN TRANSACTION
2. Inserta factura
3. Agrega detalles de todos los productos
4. Actualiza stocks
5. **Error detectado** → ROLLBACK
6. **Resultado: Todo se revierte** ✅
   - Base de datos queda como antes
   - Integridad garantizada

**Ubicación del código:** `models/FacturaVenta.php`

---

## 📊 DIAPOSITIVA 13: CARACTERÍSTICAS TÉCNICAS

### Backend:
- **Lenguaje:** PHP 8.2 puro
- **Base de Datos:** MySQL 8.0
- **Conexión:** PDO con manejo de excepciones
- **Arquitectura:** MVC simplificado

### Frontend:
- **HTML5 + CSS3**
- **Bootstrap 5** (responsive)
- **JavaScript** (validaciones y cálculos)

### Servidor:
- **XAMPP** (Apache + MySQL + PHP)

---

## 📊 DIAPOSITIVA 14: DEMOSTRACIÓN EN VIVO

### Flujo de Demostración de las 6 Pantallas:

**PARTE 1: INGRESO DE DATOS**

1. **Crear Producto** (Pantalla 1)
   - Nombre: "Camiseta Polo"
   - Talla: M, Color: Azul
   - Stock: 50, Stock mínimo: 10
   - Categoría: Camisetas
   - Proveedor: Textiles del Norte
   - ✅ Guardar y verificar mensaje de éxito

2. **Crear Cliente** (Pantalla 2)
   - Nombre: "Pedro Martínez"
   - Dirección: "Av. Central 555"
   - Correo: "pedro@email.com"
   - Teléfonos: "999-111-222, 999-333-444"
   - ✅ Guardar y verificar mensaje de éxito

**PARTE 2: ACTUALIZACIÓN DE DATOS**

3. **Editar Producto** (Pantalla 3)
   - Usar selector para buscar producto
   - Modificar stock de 50 a 30
   - ✅ Actualizar y verificar cambios

4. **Editar Cliente** (Pantalla 4)
   - Usar selector para buscar cliente
   - Modificar dirección
   - Agregar/eliminar teléfonos
   - ✅ Guardar cambios

**PARTE 3: REPORTES**

5. **Reporte Stock Mínimo** (Pantalla 5)
   - Mostrar productos con stock bajo
   - Señalar columna "Estado" (SIN STOCK, CRÍTICO, EN MÍNIMO)
   - Señalar teléfono de proveedor

6. **Reporte de Ventas** (Pantalla 6)
   - Seleccionar rango de fechas
   - Generar reporte
   - Mostrar tarjetas de estadísticas
   - Mostrar tabla de ventas

---

## 📊 DIAPOSITIVA 15: RESULTADOS Y CUMPLIMIENTO

### ✅ Requerimiento Cumplido al 100%:
✅ **2 Pantallas de Ingreso** implementadas  
✅ **2 Pantallas de Actualización** implementadas  
✅ **2 Pantallas de Reportes** implementadas  

### Aspectos Destacados:
✅ Base de datos normalizada (3FN) con 8 tablas  
✅ Seguridad: PDO con prepared statements  
✅ Transacciones SQL para integridad de datos  
✅ Interfaz responsive con Bootstrap 5  
✅ Selectores integrados en pantallas de actualización  
✅ Gestión de múltiples teléfonos por cliente  
✅ Indicadores visuales en reportes  

### Métricas del Sistema:
- **Pantallas obligatorias:** 6/6 ✅
- **Tablas en base de datos:** 8
- **Modelos PHP:** 6
- **Normalización:** 3FN completa
- **Seguridad:** Prepared statements en 100% de consultas

---

## 📊 DIAPOSITIVA 16: CONCLUSIONES

### Fortalezas del Sistema:
✅ **Integridad de datos** garantizada por transacciones  
✅ **Facilidad de uso** con interfaz intuitiva  
✅ **Seguridad** con prepared statements  
✅ **Escalabilidad** para agregar nuevas funciones  
✅ **Bajo costo** (tecnologías gratuitas)  

### Aprendizajes:
📚 Diseño de bases de datos relacionales  
📚 Implementación de transacciones  
📚 Desarrollo web con PHP puro  
📚 Integración frontend-backend  

---

## 📊 DIAPOSITIVA 17: MEJORAS FUTURAS

### Corto Plazo:
- 🔜 Sistema de login y usuarios
- 🔜 Más reportes (productos más vendidos)
- 🔜 Exportar a Excel/PDF

### Mediano Plazo:
- 🔮 Dashboard con gráficos
- 🔮 Gestión de múltiples sucursales
- 🔮 Notificaciones por email

### Largo Plazo:
- 🚀 API REST
- 🚀 App móvil
- 🚀 Integración con punto de venta

---

## 📊 DIAPOSITIVA 18: PREGUNTAS FRECUENTES

**¿Qué pasa si hay un error en la venta?**
→ Se hace ROLLBACK y no se guarda nada

**¿Se puede vender más stock del disponible?**
→ No, hay validación en frontend y backend

**¿Cómo se manejan múltiples teléfonos por cliente?**
→ Tabla separada con relación 1:N

**¿El sistema es seguro contra SQL Injection?**
→ Sí, usa prepared statements en todas las consultas

---

## 📊 DIAPOSITIVA 19: REFERENCIAS

1. Elmasri, R., & Navathe, S. B. (2015). *Fundamentals of Database Systems*. Pearson.

2. MySQL Documentation. (2024). https://dev.mysql.com/doc/

3. PHP Documentation. (2024). https://www.php.net/docs.php

4. Bootstrap Documentation. (2024). https://getbootstrap.com/docs/

5. Silberschatz, A., et al. (2019). *Database System Concepts*. McGraw-Hill.

---

## 📊 DIAPOSITIVA 20: CIERRE

### ¡Gracias por su atención!

**Sistema Web de Inventario para Tienda de Ropa**

✅ **Requerimiento 2+2+2 cumplido al 100%**  
✅ 6 pantallas funcionales implementadas  
✅ Base de datos normalizada y segura  
✅ Sistema listo para presentar  

### ¿Preguntas?

---

## 🎯 TIPS PARA LA PRESENTACIÓN:

### Antes de presentar:
1. **Practica la demostración** de las 6 pantallas en orden
2. **Verifica que XAMPP esté corriendo** (Apache + MySQL)
3. **Ten datos de prueba** listos para mostrar
4. **Prepara screenshots** como respaldo si falla internet
5. **Conoce los números:** 6 pantallas, 8 tablas, 3FN

### Durante la presentación:
1. **Enfatiza el cumplimiento exacto:** 2+2+2 (sin extras)
2. **Resalta las transacciones SQL** en el backend
3. **Muestra los selectores integrados** en edición (no requieren listado)
4. **Destaca la normalización 3FN** de la base de datos
5. **Explica prepared statements** para seguridad

### Orden sugerido de demostración (10-15 min):
1. **Introducción** (1 min) - Explicar el requerimiento 2+2+2
2. **Base de datos** (2 min) - Mostrar diagrama E-R y tablas
3. **Ingreso** (3 min) - Demostrar crear producto y cliente
4. **Actualización** (3 min) - Demostrar editar con selectores
5. **Reportes** (3 min) - Generar ambos reportes
6. **Código técnico** (2 min) - Mostrar transacción y prepared statement
7. **Conclusión** (1 min) - Enfatizar cumplimiento del 2+2+2
3. Modelo E-R y tablas (3 min)
4. Demostración en vivo (7 min)
5. Aspectos técnicos (3 min)
6. Conclusiones (2 min)

**¡Éxito en tu presentación! 🚀**
