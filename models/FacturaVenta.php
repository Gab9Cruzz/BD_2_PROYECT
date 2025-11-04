<?php

class FacturaVenta {
    private $conn;
    private $table = "facturas_venta";

    public $id;
    public $numero_factura;
    public $cliente_id;
    public $fecha_emision;
    public $subtotal;
    public $iva_total;
    public $total;
    public $forma_pago;
    public $estado;
    public $observaciones;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearVenta($detalles) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table . " 
                      (numero_factura, cliente_id, fecha_emision, subtotal, iva_total, total, forma_pago, estado, observaciones) 
                      VALUES 
                      (:numero_factura, :cliente_id, :fecha_emision, :subtotal, :iva_total, :total, :forma_pago, :estado, :observaciones)";
            
            $stmt = $this->conn->prepare($query);
            
            $this->numero_factura = htmlspecialchars(strip_tags($this->numero_factura));
            $this->forma_pago = htmlspecialchars(strip_tags($this->forma_pago));
            $this->observaciones = htmlspecialchars(strip_tags($this->observaciones));
            
            $stmt->bindParam(":numero_factura", $this->numero_factura);
            $stmt->bindParam(":cliente_id", $this->cliente_id);
            $stmt->bindParam(":fecha_emision", $this->fecha_emision);
            $stmt->bindParam(":subtotal", $this->subtotal);
            $stmt->bindParam(":iva_total", $this->iva_total);
            $stmt->bindParam(":total", $this->total);
            $stmt->bindParam(":forma_pago", $this->forma_pago);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":observaciones", $this->observaciones);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al crear la factura");
            }
            
            $id_factura = $this->conn->lastInsertId();

            $queryDetalle = "INSERT INTO detalle_factura 
                            (factura_id, producto_id, cantidad, precio_unitario, subtotal, iva_porcentaje, iva_valor, total) 
                            VALUES 
                            (:factura_id, :producto_id, :cantidad, :precio_unitario, :subtotal, :iva_porcentaje, :iva_valor, :total)";
            
            $stmtDetalle = $this->conn->prepare($queryDetalle);

            $queryStock = "UPDATE productos 
                          SET stock_actual = stock_actual - :cantidad 
                          WHERE id = :producto_id AND stock_actual >= :cantidad";
            
            $stmtStock = $this->conn->prepare($queryStock);

            $queryMovimiento = "INSERT INTO movimientos_inventario 
                               (producto_id, tipo_movimiento, cantidad, motivo, usuario) 
                               VALUES 
                               (:producto_id, 'salida', :cantidad, :motivo, :usuario)";
            
            $stmtMovimiento = $this->conn->prepare($queryMovimiento);

            foreach($detalles as $detalle) {
                $stmtDetalle->bindParam(":factura_id", $id_factura);
                $stmtDetalle->bindParam(":producto_id", $detalle['producto_id']);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precio_unitario", $detalle['precio_unitario']);
                $stmtDetalle->bindParam(":subtotal", $detalle['subtotal']);
                $stmtDetalle->bindParam(":iva_porcentaje", $detalle['iva_porcentaje']);
                $stmtDetalle->bindParam(":iva_valor", $detalle['iva_valor']);
                $stmtDetalle->bindParam(":total", $detalle['total']);
                
                if(!$stmtDetalle->execute()) {
                    throw new Exception("Error al insertar detalle de venta");
                }

                $stmtStock->bindParam(":cantidad", $detalle['cantidad']);
                $stmtStock->bindParam(":producto_id", $detalle['producto_id']);
                
                if(!$stmtStock->execute() || $stmtStock->rowCount() == 0) {
                    throw new Exception("Stock insuficiente para el producto ID: " . $detalle['producto_id']);
                }

                $motivo = "Venta - Factura #" . $this->numero_factura;
                $usuario = "vendedor";
                $stmtMovimiento->bindParam(":producto_id", $detalle['producto_id']);
                $stmtMovimiento->bindParam(":cantidad", $detalle['cantidad']);
                $stmtMovimiento->bindParam(":motivo", $motivo);
                $stmtMovimiento->bindParam(":usuario", $usuario);
                
                if(!$stmtMovimiento->execute()) {
                    throw new Exception("Error al registrar movimiento de inventario");
                }
            }

            $this->conn->commit();
            return $id_factura;

        } catch(Exception $e) {
            // Revertir transacción en caso de error
            $this->conn->rollBack();
            echo "Error en la transacción: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM vista_ventas_detalladas";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerPorId() {
        $query = "SELECT f.*, CONCAT(c.nombres, ' ', c.apellidos) AS cliente_nombre 
                  FROM " . $this->table . " f
                  LEFT JOIN clientes c ON f.cliente_id = c.id
                  WHERE f.id = :id
                  LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerDetalles() {
        $query = "SELECT d.*, p.nombre AS producto_nombre, p.marca 
                  FROM detalle_factura d
                  INNER JOIN productos p ON d.producto_id = p.id
                  WHERE d.factura_id = :factura_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":factura_id", $this->id);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerPorFechas($fecha_inicio, $fecha_fin) {
        $query = "SELECT f.*, CONCAT(c.nombres, ' ', c.apellidos) AS cliente_nombre 
                  FROM " . $this->table . " f
                  LEFT JOIN clientes c ON f.cliente_id = c.id
                  WHERE f.fecha_emision BETWEEN :fecha_inicio AND :fecha_fin
                  ORDER BY f.fecha_emision DESC";
        
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
