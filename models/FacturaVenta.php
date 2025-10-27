<?php
/**
 * Modelo FacturaVenta
 * Gestiona las ventas y sus detalles con transacciones
 */

class FacturaVenta {
    private $conn;
    private $table = "FacturaVenta";

    public $id_factura;
    public $fecha;
    public $id_cliente;
    public $metodo_pago;
    public $total;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear nueva venta con transacción
     * @param array $detalles Array de productos vendidos
     * @return bool|int ID de la factura creada o false
     */
    public function crearVenta($detalles) {
        try {
            // Iniciar transacción
            $this->conn->beginTransaction();

            // 1. Insertar factura
            $query = "INSERT INTO " . $this->table . " 
                      (id_cliente, metodo_pago, total) 
                      VALUES 
                      (:id_cliente, :metodo_pago, :total)";
            
            $stmt = $this->conn->prepare($query);
            
            $this->metodo_pago = htmlspecialchars(strip_tags($this->metodo_pago));
            
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            $stmt->bindParam(":metodo_pago", $this->metodo_pago);
            $stmt->bindParam(":total", $this->total);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al crear la factura");
            }
            
            $id_factura = $this->conn->lastInsertId();

            // 2. Insertar detalles de venta
            $queryDetalle = "INSERT INTO DetalleVenta 
                            (id_factura, id_producto, cantidad, precio_unitario, subtotal) 
                            VALUES 
                            (:id_factura, :id_producto, :cantidad, :precio_unitario, :subtotal)";
            
            $stmtDetalle = $this->conn->prepare($queryDetalle);

            // 3. Actualizar stock y registrar movimientos
            $queryStock = "UPDATE Producto 
                          SET stock = stock - :cantidad 
                          WHERE id_producto = :id_producto AND stock >= :cantidad";
            
            $stmtStock = $this->conn->prepare($queryStock);

            $queryMovimiento = "INSERT INTO MovimientoInventario 
                               (id_producto, tipo_movimiento, cantidad, descripcion) 
                               VALUES 
                               (:id_producto, 'Salida', :cantidad, :descripcion)";
            
            $stmtMovimiento = $this->conn->prepare($queryMovimiento);

            // Procesar cada detalle
            foreach($detalles as $detalle) {
                // Insertar detalle
                $stmtDetalle->bindParam(":id_factura", $id_factura);
                $stmtDetalle->bindParam(":id_producto", $detalle['id_producto']);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precio_unitario", $detalle['precio_unitario']);
                $stmtDetalle->bindParam(":subtotal", $detalle['subtotal']);
                
                if(!$stmtDetalle->execute()) {
                    throw new Exception("Error al insertar detalle de venta");
                }

                // Actualizar stock
                $stmtStock->bindParam(":cantidad", $detalle['cantidad']);
                $stmtStock->bindParam(":id_producto", $detalle['id_producto']);
                
                if(!$stmtStock->execute() || $stmtStock->rowCount() == 0) {
                    throw new Exception("Stock insuficiente para el producto ID: " . $detalle['id_producto']);
                }

                // Registrar movimiento
                $descripcion = "Venta - Factura #" . $id_factura;
                $stmtMovimiento->bindParam(":id_producto", $detalle['id_producto']);
                $stmtMovimiento->bindParam(":cantidad", $detalle['cantidad']);
                $stmtMovimiento->bindParam(":descripcion", $descripcion);
                
                if(!$stmtMovimiento->execute()) {
                    throw new Exception("Error al registrar movimiento de inventario");
                }
            }

            // Confirmar transacción
            $this->conn->commit();
            return $id_factura;

        } catch(Exception $e) {
            // Revertir transacción en caso de error
            $this->conn->rollBack();
            echo "Error en la transacción: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener todas las ventas
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM vista_ventas_completas";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener venta por ID
     */
    public function obtenerPorId() {
        $query = "SELECT fv.*, c.nombre AS cliente_nombre 
                  FROM " . $this->table . " fv
                  LEFT JOIN Cliente c ON fv.id_cliente = c.id_cliente
                  WHERE fv.id_factura = :id_factura
                  LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_factura", $this->id_factura);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener detalles de una venta
     */
    public function obtenerDetalles() {
        $query = "SELECT dv.*, p.nombre AS producto_nombre 
                  FROM DetalleVenta dv
                  INNER JOIN Producto p ON dv.id_producto = p.id_producto
                  WHERE dv.id_factura = :id_factura";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_factura", $this->id_factura);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener ventas por rango de fechas
     */
    public function obtenerPorFechas($fecha_inicio, $fecha_fin) {
        $query = "SELECT fv.*, c.nombre AS cliente_nombre 
                  FROM " . $this->table . " fv
                  LEFT JOIN Cliente c ON fv.id_cliente = c.id_cliente
                  WHERE DATE(fv.fecha) BETWEEN :fecha_inicio AND :fecha_fin
                  ORDER BY fv.fecha DESC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":fecha_inicio", $fecha_inicio);
            $stmt->bindParam(":fecha_fin", $fecha_fin);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
