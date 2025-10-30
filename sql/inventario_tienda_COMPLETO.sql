DROP DATABASE IF EXISTS inventario_tienda;
CREATE DATABASE inventario_tienda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario_tienda;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    ruc VARCHAR(13),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    ciudad VARCHAR(100),
    provincia VARCHAR(100),
    pais VARCHAR(100) DEFAULT 'Ecuador',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    marca VARCHAR(100),
    categoria_id INT,
    proveedor_id INT,
    precio_compra DECIMAL(10,2) NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    iva DECIMAL(5,2) DEFAULT 15.00,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL,
    INDEX idx_codigo (codigo),
    INDEX idx_nombre (nombre),
    INDEX idx_stock (stock_actual)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE movimientos_inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    cantidad INT NOT NULL,
    motivo VARCHAR(255),
    usuario VARCHAR(100),
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    INDEX idx_producto (producto_id),
    INDEX idx_fecha (fecha_movimiento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_identificacion ENUM('cedula', 'ruc', 'pasaporte') NOT NULL DEFAULT 'cedula',
    numero_identificacion VARCHAR(20) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    ciudad VARCHAR(100),
    provincia VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_identificacion (numero_identificacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE facturas_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_factura VARCHAR(20) NOT NULL UNIQUE,
    cliente_id INT NOT NULL,
    fecha_emision DATE NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    iva_total DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    forma_pago ENUM('efectivo', 'transferencia', 'tarjeta_debito', 'tarjeta_credito', 'cheque') NOT NULL,
    estado ENUM('pagada', 'pendiente', 'anulada') DEFAULT 'pagada',
    observaciones TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    INDEX idx_numero_factura (numero_factura),
    INDEX idx_fecha (fecha_emision),
    INDEX idx_cliente (cliente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE detalle_factura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    factura_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    iva_porcentaje DECIMAL(5,2) NOT NULL,
    iva_valor DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (factura_id) REFERENCES facturas_venta(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT,
    INDEX idx_factura (factura_id),
    INDEX idx_producto (producto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE VIEW vista_productos_stock AS
SELECT 
    p.id,
    p.codigo,
    p.nombre,
    p.marca,
    c.nombre AS categoria,
    pr.nombre AS proveedor,
    p.stock_actual,
    p.stock_minimo,
    p.precio_venta,
    p.iva,
    CASE 
        WHEN p.stock_actual = 0 THEN 'Sin stock'
        WHEN p.stock_actual <= p.stock_minimo THEN 'Stock bajo'
        ELSE 'Stock normal'
    END AS estado_stock
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
LEFT JOIN proveedores pr ON p.proveedor_id = pr.id;

CREATE VIEW vista_ventas_detalladas AS
SELECT 
    f.id,
    f.numero_factura,
    f.fecha_emision,
    CONCAT(c.nombres, ' ', c.apellidos) AS cliente,
    c.tipo_identificacion,
    c.numero_identificacion,
    f.subtotal,
    f.iva_total,
    f.total,
    f.forma_pago,
    f.estado
FROM facturas_venta f
INNER JOIN clientes c ON f.cliente_id = c.id
ORDER BY f.fecha_emision DESC;

CREATE VIEW vista_movimientos_inventario AS
SELECT 
    m.id,
    p.codigo AS codigo_producto,
    p.nombre AS producto,
    m.tipo_movimiento,
    m.cantidad,
    m.motivo,
    m.usuario,
    m.fecha_movimiento
FROM movimientos_inventario m
INNER JOIN productos p ON m.producto_id = p.id
ORDER BY m.fecha_movimiento DESC;

DELIMITER //

CREATE TRIGGER trg_restock_automatico
AFTER UPDATE ON productos
FOR EACH ROW
BEGIN
    IF NEW.stock_actual <= NEW.stock_minimo AND OLD.stock_actual > NEW.stock_minimo THEN
        INSERT INTO movimientos_inventario (producto_id, tipo_movimiento, cantidad, motivo, usuario)
        VALUES (NEW.id, 'entrada', (NEW.stock_minimo * 2), 'Re-stock automático por nivel bajo', 'SISTEMA');
        
        UPDATE productos 
        SET stock_actual = stock_actual + (NEW.stock_minimo * 2)
        WHERE id = NEW.id;
    END IF;
END//

DELIMITER ;

DELIMITER //

CREATE PROCEDURE sp_reporte_ventas_periodo(
    IN fecha_inicio DATE,
    IN fecha_fin DATE
)
BEGIN
    SELECT 
        f.numero_factura,
        f.fecha_emision,
        CONCAT(c.nombres, ' ', c.apellidos) AS cliente,
        f.subtotal,
        f.iva_total,
        f.total,
        f.forma_pago,
        f.estado
    FROM facturas_venta f
    INNER JOIN clientes c ON f.cliente_id = c.id
    WHERE f.fecha_emision BETWEEN fecha_inicio AND fecha_fin
    ORDER BY f.fecha_emision DESC;
    
    SELECT 
        COUNT(*) AS total_facturas,
        SUM(subtotal) AS total_subtotal,
        SUM(iva_total) AS total_iva,
        SUM(total) AS total_general
    FROM facturas_venta
    WHERE fecha_emision BETWEEN fecha_inicio AND fecha_fin
    AND estado = 'pagada';
END//

DELIMITER ;

INSERT INTO categorias (nombre, descripcion) VALUES
('Electrónica', 'Dispositivos y componentes electrónicos'),
('Ropa', 'Prendas de vestir y accesorios'),
('Alimentos', 'Productos alimenticios y bebidas'),
('Hogar', 'Artículos para el hogar'),
('Deportes', 'Artículos deportivos y fitness');

INSERT INTO proveedores (nombre, ruc, telefono, email, direccion, ciudad, provincia, pais) VALUES
('Distribuidora Nacional S.A.', '1790123456001', '02-2345678', 'ventas@disnacional.ec', 'Av. América N45-123', 'Quito', 'Pichincha', 'Ecuador'),
('Importadora del Pacífico', '0992345678001', '04-2567890', 'info@imppacifico.ec', 'Av. 9 de Octubre 456', 'Guayaquil', 'Guayas', 'Ecuador'),
('Comercial Andina Cía. Ltda.', '0103456789001', '07-2890123', 'contacto@comandes.ec', 'Calle Larga 12-34', 'Cuenca', 'Azuay', 'Ecuador');

INSERT INTO productos (codigo, nombre, descripcion, marca, categoria_id, proveedor_id, precio_compra, precio_venta, stock_actual, stock_minimo, iva) VALUES
('PROD001', 'Laptop HP 15-DY', 'Laptop HP 15 pulgadas, Intel i5, 8GB RAM', 'HP', 1, 1, 450.00, 650.00, 15, 5, 15.00),
('PROD002', 'Mouse Inalámbrico Logitech', 'Mouse inalámbrico ergonómico', 'Logitech', 1, 1, 12.00, 25.00, 50, 10, 15.00),
('PROD003', 'Camiseta Polo Nike', 'Camiseta deportiva manga corta', 'Nike', 2, 2, 15.00, 35.00, 30, 10, 15.00),
('PROD004', 'Arroz Premium 2kg', 'Arroz de grano largo', 'La Pradera', 3, 3, 2.50, 4.50, 100, 20, 15.00),
('PROD005', 'Balón de Fútbol', 'Balón profesional tamaño 5', 'Adidas', 5, 2, 20.00, 45.00, 25, 8, 15.00);

INSERT INTO clientes (tipo_identificacion, numero_identificacion, nombres, apellidos, telefono, email, direccion, ciudad, provincia) VALUES
('cedula', '1234567890', 'Juan Carlos', 'Pérez González', '0987654321', 'juan.perez@email.com', 'Av. Amazonas N34-567', 'Quito', 'Pichincha'),
('ruc', '0912345678001', 'María Fernanda', 'López Morales', '0998765432', 'maria.lopez@email.com', 'Av. del Ejército 234', 'Guayaquil', 'Guayas'),
('cedula', '0123456789', 'Pedro Antonio', 'Ramírez Torres', '0976543210', 'pedro.ramirez@email.com', 'Calle Bolívar 45-67', 'Cuenca', 'Azuay');

INSERT INTO movimientos_inventario (producto_id, tipo_movimiento, cantidad, motivo, usuario) VALUES
(1, 'entrada', 20, 'Compra a proveedor', 'admin'),
(2, 'entrada', 60, 'Compra a proveedor', 'admin'),
(3, 'entrada', 40, 'Compra a proveedor', 'admin'),
(4, 'entrada', 120, 'Compra a proveedor', 'admin'),
(5, 'entrada', 30, 'Compra a proveedor', 'admin'),
(1, 'salida', 5, 'Venta a cliente', 'vendedor1'),
(2, 'salida', 10, 'Venta a cliente', 'vendedor1'),
(4, 'salida', 20, 'Venta a cliente', 'vendedor1');

INSERT INTO facturas_venta (numero_factura, cliente_id, fecha_emision, subtotal, iva_total, total, forma_pago, estado) VALUES
('001-001-000001', 1, '2024-01-15', 565.22, 84.78, 650.00, 'tarjeta_credito', 'pagada'),
('001-001-000002', 2, '2024-01-16', 78.26, 11.74, 90.00, 'transferencia', 'pagada'),
('001-001-000003', 3, '2024-01-17', 86.96, 13.04, 100.00, 'efectivo', 'pagada');

INSERT INTO detalle_factura (factura_id, producto_id, cantidad, precio_unitario, subtotal, iva_porcentaje, iva_valor, total) VALUES
(1, 1, 1, 650.00, 565.22, 15.00, 84.78, 650.00),
(2, 2, 2, 25.00, 43.48, 15.00, 6.52, 50.00),
(2, 3, 1, 35.00, 30.43, 15.00, 4.57, 35.00),
(2, 4, 1, 4.50, 3.91, 15.00, 0.59, 4.50),
(3, 5, 2, 45.00, 78.26, 15.00, 11.74, 90.00),
(3, 4, 2, 4.50, 7.83, 15.00, 1.17, 9.00),
(3, 2, 1, 25.00, 21.74, 15.00, 3.26, 25.00);

INSERT INTO usuarios (usuario, password, nombre_completo, email, rol, activo) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Sistema', 'admin@tienda.ec', 'admin', 1),
('vendedor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos Vendedor', 'vendedor@tienda.ec', 'vendedor', 1);
