<?php
//require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
// Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.

require_once 'Conexion.php';

class Clientes {
    private $id;
    private $nombre;
    private $apellido;
    private $direccion;
    private $telefono;
    private $documento;
    private $tipo_documento;
//la constante TABLA define el nombre de la tabla en la base de datos asociada a esta clase.
    const TABLA = 'clientes';

       // Getters son funciones que devuelven el valor de una propiedad privada de una clase.
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getDireccion() { return $this->direccion; }
    public function getTelefono() { return $this->telefono; }
    public function getDocumento() { return $this->documento; }
    public function getTipoDocumento() { return $this->tipo_documento; }

   // Setters son funciones que permiten asignar un valor a una propiedad privada de una clase.
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setDocumento($documento) { $this->documento = $documento; }
    public function setTipoDocumento($tipo_documento) { $this->tipo_documento = $tipo_documento; }

// Constructor es una función especial que se llama automáticamente cuando se crea un objeto de la clase.
    public function __construct($nombre, $apellido, $direccion, $telefono, $documento, $tipo_documento, $id = null) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->documento = $documento;
        $this->tipo_documento = $tipo_documento;
        $this->id = $id;
    }

    // Guardar (Insertar o Modificar)
    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            //self::TABLA hace referencia a la constante TABLA definida en la clase actual.
            $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET 
                nombre = :nombre, 
                apellido = :apellido, 
                direccion = :direccion, 
                telefono = :telefono, 
                documento = :documento, 
                tipo_documento = :tipo_documento 
                WHERE id = :id');
                //bindparam se utiliza para vincular un valor a un parámetro en una consulta preparada, mejorando la seguridad y previniendo inyecciones SQL.
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':apellido', $this->apellido);
            $consulta->bindParam(':direccion', $this->direccion);
            $consulta->bindParam(':telefono', $this->telefono);
            $consulta->bindParam(':documento', $this->documento);
            $consulta->bindParam(':tipo_documento', $this->tipo_documento);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            echo $this->nombre;
            echo $this->apellido;
            echo $this->direccion;
            echo $this->telefono;

            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' 
                (nombre, apellido, direccion, telefono, documento, tipo_documento) 
                VALUES (:nombre, :apellido, :direccion, :telefono, :documento, :tipo_documento)');
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':apellido', $this->apellido);
            $consulta->bindParam(':direccion', $this->direccion);
            $consulta->bindParam(':telefono', $this->telefono);
            $consulta->bindParam(':documento', $this->documento);
            $consulta->bindParam(':tipo_documento', $this->tipo_documento);
            $consulta->execute();
           /*CREATE TABLE `clientes` (
                `id` int(11) NOT NULL,
                `nombre` varchar(50) DEFAULT NULL,
                `apellido` varchar(50) DEFAULT NULL,
                `direccion` varchar(100) DEFAULT NULL,
                `telefono` varchar(50) DEFAULT NULL,
                `documento` varchar(20) DEFAULT NULL,
                `tipo_documento` varchar(20) DEFAULT NULL
                ) ENGINE=I*/
                       $this->id = $conexion->lastInsertId();
        }
        $conexion = null;
         //conexion a null para cerrar la conexión a la base de datos. Esto libera los recursos asociados con la conexión y evita problemas de rendimiento
        // y consumo innecesario de recursos. 
    }

    // Buscar por ID
    public static function buscarPorId($id) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE id = :id');
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        $registro = $consulta->fetch();
        if ($registro) {
            return new self(
                $registro['nombre'],
                $registro['apellido'],
                $registro['direccion'],
                $registro['telefono'],
                $registro['documento'],
                $registro['tipo_documento'],
                $registro['id']
            );
        } else {
            return false;
            //new self hace referencia a la clase actual, permitiendo crear una nueva instancia de la misma.
        }
    }
    //crear funcion buscar por nombre, apellido, direccion, telefono, documento, tipo_documento por cualquiera de ellos campos.
public static function buscarPor($valor = null) {
    $conexion = new Conexion();
    $sql = 'SELECT * FROM ' . self::TABLA;
    
    if (!empty($valor)) {
        $sql .= ' WHERE nombre LIKE :nombre OR apellido LIKE :apellido OR direccion LIKE :direccion OR telefono LIKE :telefono OR documento LIKE :documento';
        $consulta = $conexion->prepare($sql);
        $valorLike = '%' . $valor . '%';
        $consulta->bindValue(':apellido', $valorLike);
        $consulta->bindValue(':nombre', $valorLike);
        $consulta->bindValue(':direccion', $valorLike);
        $consulta->bindValue(':telefono', $valorLike);
        $consulta->bindValue(':documento', $valorLike);
    } else {
        $consulta = $conexion->prepare($sql);
    }

    $consulta->execute();
    return $consulta->fetchAll();
}



      // Recuperar todos sirve para obtener todos los registros de la tabla.
    public static function recuperarTodos() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' ORDER BY apellido, nombre');
        $consulta->execute();
        return $consulta->fetchAll();
    }

    // Borrar registro
    public static function borrarRegistro($id) {
        try {
            $conexion = new Conexion();
            $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id = ?');
            $consulta->bindParam(1, $id);
            $consulta->execute();
        } catch (PDOException $e) {{//el catch captura excepciones específicas, en este caso PDOException, 
                                    // que son errores relacionados con la base de datos.
            echo $e->getMessage();
        }
    }
}
}
?>
