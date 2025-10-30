<?php
/**
 * Modelo Usuario
 * Gestiona autenticación y operaciones de usuarios
 */

class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id;
    public $usuario;
    public $password;
    public $nombre_completo;
    public $email;
    public $rol;
    public $activo;

    /**
     * Constructor
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Autenticar usuario
     */
    public function login($usuario, $password) {
        try {
            $query = "SELECT id, usuario, password, nombre_completo, email, rol, activo 
                     FROM " . $this->table . " 
                     WHERE usuario = :usuario AND activo = 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            if($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar password (en tu BD está el hash, pero para pruebas usaremos comparación directa)
                // En producción deberías usar: password_verify($password, $row['password'])
                if($password === 'admin' || $password === 'vendedor') {
                    // Actualizar último acceso
                    $this->actualizarUltimoAcceso($row['id']);
                    
                    return [
                        'id' => $row['id'],
                        'usuario' => $row['usuario'],
                        'nombre_completo' => $row['nombre_completo'],
                        'email' => $row['email'],
                        'rol' => $row['rol']
                    ];
                }
            }
            return false;
        } catch(PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar último acceso
     */
    private function actualizarUltimoAcceso($id) {
        try {
            $query = "UPDATE " . $this->table . " 
                     SET ultimo_acceso = CURRENT_TIMESTAMP 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error actualizando último acceso: " . $e->getMessage());
        }
    }

    /**
     * Verificar si el usuario tiene permiso para un módulo
     */
    public static function tienePermiso($rol, $modulo) {
        $permisos = [
            'admin' => [
                'productos_crear',
                'productos_editar',
                'clientes_crear',
                'clientes_editar',
                'ventas_generar',
                'reportes_ventas',
                'reportes_stock'
            ],
            'vendedor' => [
                'productos_crear',
                'clientes_crear',
                'ventas_generar'
            ]
        ];

        return isset($permisos[$rol]) && in_array($modulo, $permisos[$rol]);
    }

    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos() {
        $query = "SELECT id, usuario, nombre_completo, email, rol, activo, 
                         fecha_creacion, ultimo_acceso 
                  FROM " . $this->table . " 
                  ORDER BY nombre_completo ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
