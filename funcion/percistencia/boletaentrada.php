<?php
//require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
// Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.

require_once 'Conexion.php';

class BoletaEntrada {
    private $id;
    private $moneda;
    private $fecha;
    private $id_cliente;
//la constante TABLA define el nombre de la tabla en la base de datos asociada a esta clase.
    const TABLA = 'boleta_entrada';
   // Getters son funciones que devuelven el valor de una propiedad privada de una clase.
    public function getId() { return $this->id; }
    public function getMoneda() { return $this->moneda; }
    public function getFecha() { return $this->fecha; }
    public function getIdCliente() { return $this->id_cliente; }
 // Setters son funciones que permiten asignar un valor a una propiedad privada de una clase.
    public function setId($id) { $this->id = $id; }
    public function setMoneda($moneda) { $this->moneda = $moneda; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setIdCliente($id_cliente) { $this->id_cliente = $id_cliente; }
// Constructor es una función especial que se llama automáticamente cuando se crea un objeto de la clase.
    public function __construct($moneda, $fecha, $id_cliente, $id = null) {
        $this->moneda = $moneda;
        $this->fecha = $fecha;
        $this->id_cliente = $id_cliente;
        $this->id = $id;
    }
//bindparam se utiliza para vincular un valor a un parámetro en una consulta preparada, mejorando la seguridad y previniendo inyecciones SQL.
    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            $sql = 'UPDATE ' . self::TABLA . ' SET moneda = :moneda, fecha = :fecha, id_cliente = :id_cliente WHERE id = :id';
           $_SESSION['MSG'] = $sql;
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':moneda', $this->moneda);
            $consulta->bindParam(':fecha', $this->fecha);
            $consulta->bindParam(':id_cliente', $this->id_cliente);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            //self::TABLA hace referencia a la constante TABLA definida en la clase actual.
            $sql = 'INSERT INTO ' . self::TABLA . ' (moneda, fecha, id_cliente) VALUES (:moneda, :fecha, :id_cliente)';
            $_SESSION['MSG'] = $sql;
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':moneda', $this->moneda);
            $consulta->bindParam(':fecha', $this->fecha);
            $consulta->bindParam(':id_cliente', $this->id_cliente);
            $consulta->execute();
            $this->id = $conexion->lastInsertId();
        }
        $conexion = null;
        //conexion a null para cerrar la conexión a la base de datos. Esto libera los recursos asociados con la conexión y evita problemas de rendimiento
        // y consumo innecesario de recursos. 
    }

    public static function buscarPorId($id) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE id = :id');
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        $registro = $consulta->fetch();
        if ($registro) {
            return new self($registro['moneda'], $registro['fecha'], $registro['id_cliente'], $registro['id']);
        } else {
            return false;
            //new self hace referencia a la clase actual, permitiendo crear una nueva instancia de la misma.
        }
    }
public static function buscarPor($valor = null) {
    $conexion = new Conexion();
     $sql = 'SELECT b.* FROM ' . self::TABLA . ' b
            JOIN clientes c ON b.id_cliente = c.id';
    
    if (!empty($valor)) {
        $sql .= ' WHERE b.fecha LIKE :fecha OR b.moneda LIKE :moneda OR c.nombre LIKE :nombre';
        $consulta = $conexion->prepare($sql);
        $valorLike = '%' . $valor . '%';
        $consulta->bindValue(':fecha', $valorLike);
        $consulta->bindValue(':moneda', $valorLike);
        $consulta->bindValue(':nombre', $valorLike);
    } else {
        $consulta = $conexion->prepare($sql);
    }

    $consulta->execute();
    return $consulta->fetchAll();
}



    // Recuperar todos sirve para obtener todos los registros de la tabla.
    public static function recuperarTodos() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' ORDER BY fecha DESC');
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public static function borrarRegistro($id) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id = ?');
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    public static function obtenerProductosPorBoleta($id_boleta) {
    $conexion = new Conexion();
    $consulta = "SELECT * FROM objetos WHERE id_boleta = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(1, $id_boleta);
    $stmt->execute();
    return $stmt->fetchAll();
    }
}
?>