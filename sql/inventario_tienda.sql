-- ============================================
-- Sistema de Inventario para Tienda de Ropa
-- Base de datos: inventario_tienda
-- Charset: utf8mb4
-- Engine: InnoDB
-- Normalización: 3FN
-- Requerimiento: 2 pantallas ingreso + 2 actualización + 2 reportes
-- ============================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS inventario_tienda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario_tienda;

-- ============================================
-- TABLA: Proveedor
-- Descripción: Almacena información de proveedores
-- ============================================
CREATE TABLE IF NOT EXISTS Proveedor (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    direccion VARCHAR(150),
    INDEX idx_nombre_proveedor (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: Categoria
-- Descripción: Categorías de productos (ropa)
-- ============================================
CREATE TABLE IF NOT EXISTS Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL UNIQUE,
    descripcion VARCHAR(150),
    INDEX idx_nombre_categoria (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: Producto
-- Descripción: Productos de ropa en inventario
-- ============================================
CREATE TABLE IF NOT EXISTS Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    talla VARCHAR(10),
    color VARCHAR(30),
    precio DECIMAL(10,2) DEFAULT 0,
    stock INT DEFAULT 0 CHECK (stock >= 0),
    stock_minimo INT DEFAULT 0 CHECK (stock_minimo >= 0),
    id_categoria INT NOT NULL,
    id_proveedor INT,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria) 
        ON UPDATE CASCADE 
        ON DELETE RESTRICT,
    FOREIGN KEY (id_proveedor) REFERENCES Proveedor(id_proveedor) 
        ON UPDATE CASCADE 
        ON DELETE SET NULL,
    INDEX idx_nombre_producto (nombre),
    INDEX idx_stock (stock)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: Cliente
-- Descripción: Información de clientes
-- ============================================
CREATE TABLE IF NOT EXISTS Cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(150),
    correo VARCHAR(100),
    INDEX idx_nombre_cliente (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: Telefono_Cliente
-- Descripción: Teléfonos de clientes (relación 1:N)
-- ============================================
CREATE TABLE IF NOT EXISTS Telefono_Cliente (
    id_telefono INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente) 
        ON DELETE CASCADE,
    INDEX idx_cliente_telefono (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: FacturaVenta
-- Descripción: Facturas de ventas realizadas
-- ============================================
CREATE TABLE IF NOT EXISTS FacturaVenta (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_cliente INT,
    metodo_pago VARCHAR(30) NOT NULL,
    total DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente) 
        ON UPDATE CASCADE 
        ON DELETE SET NULL,
    INDEX idx_fecha_factura (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: DetalleVenta
-- Descripción: Detalle de productos vendidos
-- ============================================
CREATE TABLE IF NOT EXISTS DetalleVenta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_factura INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_factura) REFERENCES FacturaVenta(id_factura) 
        ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto) 
        ON DELETE RESTRICT,
    INDEX idx_factura_detalle (id_factura),
    INDEX idx_producto_detalle (id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: MovimientoInventario
-- Descripción: Registro de movimientos de stock
-- ============================================
CREATE TABLE IF NOT EXISTS MovimientoInventario (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    tipo_movimiento ENUM('Entrada','Salida') NOT NULL,
    cantidad INT NOT NULL,
    fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
    descripcion VARCHAR(200),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto) 
        ON DELETE RESTRICT,
    INDEX idx_fecha_movimiento (fecha_movimiento),
    INDEX idx_producto_movimiento (id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DATOS DE EJEMPLO
-- ============================================

-- Insertar categorías de ejemplo
INSERT INTO Categoria (nombre, descripcion) VALUES
('Camisetas', 'Camisetas y polos'),
('Pantalones', 'Pantalones y jeans'),
('Vestidos', 'Vestidos casuales y formales'),
('Accesorios', 'Gorras, cinturones, bufandas'),
('Calzado', 'Zapatos y zapatillas');

-- Insertar proveedores de ejemplo
INSERT INTO Proveedor (nombre, telefono, correo, direccion) VALUES
('Textiles del Norte', '555-1234', 'ventas@texnorte.com', 'Av. Industrial 123'),
('Moda Express', '555-5678', 'contacto@modaexpress.com', 'Calle Comercio 456'),
('Distribuidora La Fashion', '555-9012', 'info@lafashion.com', 'Boulevard Central 789');

-- Insertar productos de ejemplo
INSERT INTO Producto (nombre, talla, color, precio, stock, stock_minimo, id_categoria, id_proveedor) VALUES
('Camiseta Básica', 'M', 'Blanco', 15.99, 50, 10, 1, 1),
('Camiseta Básica', 'L', 'Negro', 15.99, 30, 10, 1, 1),
('Jean Slim Fit', '32', 'Azul', 45.99, 25, 5, 2, 2),
('Vestido Casual', 'S', 'Rojo', 59.99, 15, 5, 3, 3),
('Gorra Deportiva', 'Única', 'Negro', 12.50, 40, 8, 4, 1),
('Zapatillas Casual', '42', 'Blanco', 79.99, 20, 5, 5, 2);

-- Insertar clientes de ejemplo
INSERT INTO Cliente (nombre, direccion, correo) VALUES
('María González', 'Av. Principal 100', 'maria.g@email.com'),
('Carlos Ramírez', 'Calle 5 #234', 'carlos.r@email.com'),
('Ana López', 'Jr. Los Pinos 567', 'ana.lopez@email.com');

-- Insertar teléfonos de clientes
INSERT INTO Telefono_Cliente (id_cliente, telefono) VALUES
(1, '999-111-222'),
(1, '999-111-223'),
(2, '999-333-444'),
(3, '999-555-666');

-- ============================================
-- VISTAS (2 implementadas y usadas en el sistema)
-- ============================================

-- Vista 1: Productos con stock bajo mínimo
-- Usada en: views/reportes/stock_minimo.php
CREATE OR REPLACE VIEW vista_stock_bajo AS
SELECT 
    p.id_producto,
    p.nombre,
    p.talla,
    p.color,
    p.stock,
    p.stock_minimo,
    c.nombre AS categoria,
    pr.nombre AS proveedor,
    pr.telefono AS telefono_proveedor,
    CASE 
        WHEN p.stock = 0 THEN 'SIN STOCK'
        WHEN p.stock < p.stock_minimo THEN 'CRÍTICO'
        WHEN p.stock = p.stock_minimo THEN 'EN MÍNIMO'
    END AS estado_stock
FROM Producto p
INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
LEFT JOIN Proveedor pr ON p.id_proveedor = pr.id_proveedor
WHERE p.stock <= p.stock_minimo
ORDER BY p.stock ASC;

-- Vista 2: Ventas completas con detalles
-- Usada en: Consultas generales de ventas
CREATE OR REPLACE VIEW vista_ventas_completas AS
SELECT 
    fv.id_factura,
    fv.fecha,
    c.nombre AS cliente,
    c.correo AS correo_cliente,
    fv.metodo_pago,
    fv.total,
    COUNT(dv.id_detalle) AS cantidad_items
FROM FacturaVenta fv
LEFT JOIN Cliente c ON fv.id_cliente = c.id_cliente
LEFT JOIN DetalleVenta dv ON fv.id_factura = dv.id_factura
GROUP BY fv.id_factura, fv.fecha, c.nombre, c.correo, fv.metodo_pago, fv.total
ORDER BY fv.fecha DESC;

-- ============================================
-- STORED PROCEDURES (1 implementado y usado en el sistema)
-- ============================================

-- Procedimiento: Reporte de ventas por rango de fechas
-- Usada en: views/reportes/ventas.php
DELIMITER //
CREATE PROCEDURE sp_reporte_ventas_fechas(
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE
)
BEGIN
    -- Resultado 1: Lista de ventas en el rango de fechas
    SELECT 
        fv.id_factura,
        fv.fecha,
        c.nombre AS cliente,
        c.correo,
        fv.metodo_pago,
        fv.total,
        COUNT(dv.id_detalle) AS cantidad_items
    FROM FacturaVenta fv
    LEFT JOIN Cliente c ON fv.id_cliente = c.id_cliente
    LEFT JOIN DetalleVenta dv ON fv.id_factura = dv.id_factura
    WHERE DATE(fv.fecha) BETWEEN p_fecha_inicio AND p_fecha_fin
    GROUP BY fv.id_factura, fv.fecha, c.nombre, c.correo, fv.metodo_pago, fv.total
    ORDER BY fv.fecha DESC;
    
    -- Resultado 2: Estadísticas del período
    SELECT 
        COUNT(*) AS total_ventas,
        COALESCE(SUM(total), 0) AS ingresos_totales,
        COALESCE(AVG(total), 0) AS promedio_venta,
        COALESCE(MIN(total), 0) AS venta_minima,
        COALESCE(MAX(total), 0) AS venta_maxima
    FROM FacturaVenta
    WHERE DATE(fecha) BETWEEN p_fecha_inicio AND p_fecha_fin;
END //
DELIMITER ;

-- ============================================
-- TRIGGERS (No implementados - opcionales)
-- ============================================
-- Los triggers no son necesarios para el funcionamiento básico
-- La validación de stock se hace en la capa de aplicación (PHP)
-- con transacciones que garantizan integridad de datos

-- ============================================
-- EJEMPLO DE USO DEL STORED PROCEDURE
-- ============================================

-- Generar reporte de ventas del mes actual:
-- CALL sp_reporte_ventas_fechas('2025-10-01', '2025-10-31');

