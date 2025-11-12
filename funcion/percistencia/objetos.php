<?php
//require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
// Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
require_once 'Conexion.php';

class Objetos {
    private $id;
    private $id_boleta;
    private $cantidad;
    private $nombre;
    private $descripcion;
    private $valor_esperado;
    private $foto;
    private $id_estadoRemate;
    private $estadoVenta;
//la constante TABLA define el nombre de la tabla en la base de datos asociada a esta clase.
    const TABLA = 'objetos';
   // Getters son funciones que devuelven el valor de una propiedad privada de una clase.
    public function getId() { return $this->id; }
    public function getIdBoleta() { return $this->id_boleta; }
    public function getCantidad() { return $this->cantidad; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getValorEsperado() { return $this->valor_esperado; }
    public function getFoto() { return $this->foto; }
    public function getIdEstadoRemate() { return $this->id_estadoRemate;}
 // Setters son funciones que permiten asignar un valor a una propiedad privada de una clase.
    public function setId($id) { $this->id = $id; }
    public function setIdBoleta($id_boleta) { $this->id_boleta = $id_boleta; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setValorEsperado($valor_esperado) { $this->valor_esperado = $valor_esperado; }
    public function setFoto($foto) { $this->foto = $foto; }
    public function setEstadoVenta($estadoVenta) {   $this->estadoVenta = $estadoVenta; }

// Constructor es una función especial que se llama automáticamente cuando se crea un objeto de la clase.
    public function __construct($id_boleta, $cantidad, $nombre, $descripcion, $valor_esperado, $id = null, $id_estadoRemate = '', $estadoVenta = '', $foto = '') {
        $this->id_boleta = $id_boleta;
        $this->cantidad = $cantidad;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->valor_esperado = $valor_esperado;
        $this->foto = $foto;
        $this->id = $id;
        $this->id_estadoRemate = $id_estadoRemate;
        $this->estadoVenta = $estadoVenta;
    }
    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            //self::TABLA hace referencia a la constante TABLA definida en la clase actual.
            $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET id_boleta = :id_boleta, cantidad = :cantidad, nombre = :nombre, descripcion = :descripcion, valor_esperado = :valor_esperado, foto = :foto,
            id_estadoRemate = :id_estadoRemate, estadoVenta = :estadoVenta WHERE id = :id');
           //bindparam se utiliza para vincular un valor a un parámetro en una consulta preparada,
           // mejorando la seguridad y previniendo inyecciones SQL.
            $consulta->bindParam(':id_boleta', $this->id_boleta);
            $consulta->bindParam(':cantidad', $this->cantidad);
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':descripcion', $this->descripcion);
            $consulta->bindParam(':valor_esperado', $this->valor_esperado);
            $consulta->bindParam(':foto', $this->foto);
            $consulta->bindParam(':id', $this->id);
            $consulta->bindParam(':id_estadoRemate', $this->id_estadoRemate);
            $consulta->bindParam(':estadoVenta', $this->estadoVenta);
            $consulta->execute();
        } else {
            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (id_boleta, cantidad, nombre, descripcion, valor_esperado, foto,
            id_estadoRemate, estadoVenta) VALUES (:id_boleta, :cantidad, :nombre, :descripcion, :valor_esperado, :foto, :id_estadoRemate, :estadoVenta)');
            $consulta->bindParam(':id_boleta', $this->id_boleta);
            $consulta->bindParam(':cantidad', $this->cantidad);
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':descripcion', $this->descripcion);
            $consulta->bindParam(':valor_esperado', $this->valor_esperado);
            $consulta->bindParam(':foto', $this->foto);
            $consulta->bindParam(':id_estadoRemate', $this->id_estadoRemate);
            $consulta->bindParam(':estadoVenta', $this->estadoVenta);
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
            return new self($registro['id_boleta'], $registro['cantidad'], $registro['nombre'], $registro['descripcion'], $registro['valor_esperado'], $registro['id'], $registro['id_estadoRemate'], $registro['estadoVenta'], $registro['foto']);
        } else {
            return false;
            //new self hace referencia a la clase actual, permitiendo crear una nueva instancia de la misma.
        }
    }
    // Recuperar todos sirve para obtener todos los registros de la tabla.

    public static function recuperarTodos() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA);
        $consulta->execute();
        return $consulta->fetchAll();
    }

public static function borrarRegistro($id) {
    try {
        $conexion = new Conexion(); 
        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id = ?');
        $consulta->bindParam(1, $id, PDO::PARAM_INT);
        $consulta->execute();

        // Verificamos si se borró al menos una fila
        return $consulta->rowCount() > 0;
    } catch (PDOException $e) {
        error_log("Error al borrar objeto con ID $id: " . $e->getMessage());
        return false;
    }
}

    public static function eliminarPorBoleta($id_boleta) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id_boleta = ?');
        $consulta->bindParam(1, $id_boleta);
        $consulta->execute();
    }

    public static function recuperarTodosConMoneda() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT o.*, b.moneda FROM ' . self::TABLA . ' o JOIN boleta_entrada b ON o.id_boleta = b.id WHERE LOWER(b.moneda) IN ("pesos", "usd")');
        $consulta->execute();
        return $consulta->fetchAll();
    }


}
?>
