<?php
/**
 * Modelo Producto
 * Gestiona las operaciones CRUD de productos
 */

class Producto {
    private $conn;
    private $table = "productos";

    public $id;
    public $codigo;
    public $nombre;
    public $descripcion;
    public $marca;
    public $categoria_id;
    public $proveedor_id;
    public $precio_compra;
    public $precio_venta;
    public $stock_actual;
    public $stock_minimo;
    public $iva;

    /**
     * Constructor
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT p.*, c.nombre AS categoria_nombre, pr.nombre AS proveedor_nombre 
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
                  ORDER BY p.nombre ASC";
        
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
        $query = "SELECT p.*, c.nombre AS categoria_nombre, pr.nombre AS proveedor_nombre 
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
                  WHERE p.id = :id
                  LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            
            $row = $stmt->fetch();
            if($row) {
                $this->codigo = $row['codigo'];
                $this->nombre = $row['nombre'];
                $this->descripcion = $row['descripcion'];
                $this->marca = $row['marca'];
                $this->precio_compra = $row['precio_compra'];
                $this->precio_venta = $row['precio_venta'];
                $this->iva = $row['iva'];
                $this->stock_actual = $row['stock_actual'];
                $this->stock_minimo = $row['stock_minimo'];
                $this->categoria_id = $row['categoria_id'];
                $this->proveedor_id = $row['proveedor_id'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (codigo, nombre, descripcion, marca, precio_compra, precio_venta, iva, stock_actual, stock_minimo, categoria_id, proveedor_id) 
                  VALUES 
                  (:codigo, :nombre, :descripcion, :marca, :precio_compra, :precio_venta, :iva, :stock_actual, :stock_minimo, :categoria_id, :proveedor_id)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->codigo = htmlspecialchars(strip_tags($this->codigo));
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->marca = htmlspecialchars(strip_tags($this->marca));
            
            $stmt->bindParam(":codigo", $this->codigo);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":marca", $this->marca);
            $stmt->bindParam(":precio_compra", $this->precio_compra);
            $stmt->bindParam(":precio_venta", $this->precio_venta);
            $stmt->bindParam(":iva", $this->iva);
            $stmt->bindParam(":stock_actual", $this->stock_actual);
            $stmt->bindParam(":stock_minimo", $this->stock_minimo);
            $stmt->bindParam(":categoria_id", $this->categoria_id);
            $stmt->bindParam(":proveedor_id", $this->proveedor_id);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET codigo = :codigo,
                      nombre = :nombre,
                      descripcion = :descripcion, 
                      marca = :marca,
                      precio_compra = :precio_compra,
                      precio_venta = :precio_venta,
                      iva = :iva,
                      stock_actual = :stock_actual, 
                      stock_minimo = :stock_minimo, 
                      categoria_id = :categoria_id, 
                      proveedor_id = :proveedor_id
                  WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->codigo = htmlspecialchars(strip_tags($this->codigo));
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->marca = htmlspecialchars(strip_tags($this->marca));
            
            $stmt->bindParam(":codigo", $this->codigo);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":marca", $this->marca);
            $stmt->bindParam(":precio_compra", $this->precio_compra);
            $stmt->bindParam(":precio_venta", $this->precio_venta);
            $stmt->bindParam(":iva", $this->iva);
            $stmt->bindParam(":stock_actual", $this->stock_actual);
            $stmt->bindParam(":stock_minimo", $this->stock_minimo);
            $stmt->bindParam(":categoria_id", $this->categoria_id);
            $stmt->bindParam(":proveedor_id", $this->proveedor_id);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function actualizarStock($cantidad, $tipo) {
        if($tipo == 'entrada') {
            $query = "UPDATE " . $this->table . " 
                      SET stock_actual = stock_actual + :cantidad 
                      WHERE id = :id";
        } else if($tipo == 'salida') {
            $query = "UPDATE " . $this->table . " 
                      SET stock_actual = stock_actual - :cantidad 
                      WHERE id = :id AND stock_actual >= :cantidad";
        } else {
            return false;
        }
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute() && $stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerStockBajo() {
        $query = "SELECT * FROM vista_productos_stock WHERE estado_stock IN ('Sin stock', 'Stock bajo')";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
