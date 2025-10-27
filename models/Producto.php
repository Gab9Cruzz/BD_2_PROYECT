<?php
/**
 * Modelo Producto
 * Gestiona las operaciones CRUD de productos
 */

class Producto {
    private $conn;
    private $table = "Producto";

    // Propiedades del producto
    public $id_producto;
    public $nombre;
    public $talla;
    public $color;
    public $precio;
    public $stock;
    public $stock_minimo;
    public $id_categoria;
    public $id_proveedor;

    /**
     * Constructor
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtener todos los productos
     */
    public function obtenerTodos() {
        $query = "SELECT p.*, c.nombre AS categoria_nombre, pr.nombre AS proveedor_nombre 
                  FROM " . $this->table . " p
                  INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
                  LEFT JOIN Proveedor pr ON p.id_proveedor = pr.id_proveedor
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

    /**
     * Obtener un producto por ID
     */
    public function obtenerPorId() {
        $query = "SELECT p.*, c.nombre AS categoria_nombre, pr.nombre AS proveedor_nombre 
                  FROM " . $this->table . " p
                  INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
                  LEFT JOIN Proveedor pr ON p.id_proveedor = pr.id_proveedor
                  WHERE p.id_producto = :id_producto
                  LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_producto", $this->id_producto);
            $stmt->execute();
            
            $row = $stmt->fetch();
            if($row) {
                $this->nombre = $row['nombre'];
                $this->talla = $row['talla'];
                $this->color = $row['color'];
                $this->precio = $row['precio'];
                $this->stock = $row['stock'];
                $this->stock_minimo = $row['stock_minimo'];
                $this->id_categoria = $row['id_categoria'];
                $this->id_proveedor = $row['id_proveedor'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Crear nuevo producto
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, talla, color, precio, stock, stock_minimo, id_categoria, id_proveedor) 
                  VALUES 
                  (:nombre, :talla, :color, :precio, :stock, :stock_minimo, :id_categoria, :id_proveedor)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->talla = htmlspecialchars(strip_tags($this->talla));
            $this->color = htmlspecialchars(strip_tags($this->color));
            
            // Bind de parámetros
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":talla", $this->talla);
            $stmt->bindParam(":color", $this->color);
            $stmt->bindParam(":precio", $this->precio);
            $stmt->bindParam(":stock", $this->stock);
            $stmt->bindParam(":stock_minimo", $this->stock_minimo);
            $stmt->bindParam(":id_categoria", $this->id_categoria);
            $stmt->bindParam(":id_proveedor", $this->id_proveedor);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualizar producto
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, 
                      talla = :talla, 
                      color = :color, 
                      precio = :precio,
                      stock = :stock, 
                      stock_minimo = :stock_minimo, 
                      id_categoria = :id_categoria, 
                      id_proveedor = :id_proveedor
                  WHERE id_producto = :id_producto";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->talla = htmlspecialchars(strip_tags($this->talla));
            $this->color = htmlspecialchars(strip_tags($this->color));
            
            // Bind de parámetros
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":talla", $this->talla);
            $stmt->bindParam(":color", $this->color);
            $stmt->bindParam(":precio", $this->precio);
            $stmt->bindParam(":stock", $this->stock);
            $stmt->bindParam(":stock_minimo", $this->stock_minimo);
            $stmt->bindParam(":id_categoria", $this->id_categoria);
            $stmt->bindParam(":id_proveedor", $this->id_proveedor);
            $stmt->bindParam(":id_producto", $this->id_producto);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Eliminar producto
     */
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id_producto = :id_producto";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_producto", $this->id_producto);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualizar stock del producto
     */
    public function actualizarStock($cantidad, $tipo) {
        if($tipo == 'Entrada') {
            $query = "UPDATE " . $this->table . " 
                      SET stock = stock + :cantidad 
                      WHERE id_producto = :id_producto";
        } else if($tipo == 'Salida') {
            $query = "UPDATE " . $this->table . " 
                      SET stock = stock - :cantidad 
                      WHERE id_producto = :id_producto AND stock >= :cantidad";
        } else {
            return false;
        }
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":id_producto", $this->id_producto);
            
            if($stmt->execute() && $stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener productos con stock bajo
     */
    public function obtenerStockBajo() {
        $query = "SELECT * FROM vista_stock_bajo";
        
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
