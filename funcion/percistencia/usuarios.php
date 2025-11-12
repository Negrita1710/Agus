<?php
//require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
// Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
require_once 'Conexion.php';

class Usuarios {
    private $id;
    private $nombre;
    private $contrasena;
    private $permisos;
//la constante TABLA define el nombre de la tabla en la base de datos asociada a esta clase.
    const TABLA = 'usuarios';
   // Getters son funciones que devuelven el valor de una propiedad privada de una clase.
    public function getId()               { return $this->id; }
    public function getNombre()           { return $this->nombre; }
    public function getContrasena()       { return $this->contrasena; }
    public function getPermisos()         { return $this->permisos; }
 // Setters son funciones que permiten asignar un valor a una propiedad privada de una clase.
    public function setId($id)            { $this->id = $id; }
    public function setNombre($nombre)    { $this->nombre = $nombre; }
    public function setContrasena($contrasena) { $this->contrasena = $contrasena; }
    public function setPermisos($permisos)     { $this->permisos = $permisos; }

// Constructor es una función especial que se llama automáticamente cuando se crea un objeto de la clase.
    public function __construct($nombre, $contrasena, $permisos, $id = null) {
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        $this->permisos = $permisos;
        $this->id = $id;
    }
    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            //self::TABLA hace referencia a la constante TABLA definida en la clase actual.
            $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET 
                nombre = :nombre, 
                contrasena = :contrasena, 
                permisos = :permisos 
                WHERE id = :id');
                //bindparam se utiliza para vincular un valor a un parámetro en una consulta preparada, 
                // mejorando la seguridad y previniendo inyecciones SQL.
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':contrasena', $this->contrasena);
            $consulta->bindParam(':permisos', $this->permisos);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' 
                (nombre, contrasena, permisos) VALUES (:nombre, :contrasena, :permisos)');
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':contrasena', $this->contrasena);
            $consulta->bindParam(':permisos', $this->permisos);
            $consulta->execute();
            $this->id = $conexion->lastInsertId();
        }
        $conexion = null;
         //conexion a null para cerrar la conexión a la base de datos. Esto libera los recursos asociados con la conexión y evita problemas de rendimiento
        // y consumo innecesario de recursos. 
    }

    public static function buscarPorId($id) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT nombre, contrasena, permisos, id FROM ' . self::TABLA . ' WHERE id = :id');
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        $registro = $consulta->fetch();
        if ($registro) {
            return new self(
                $registro['nombre'],
                $registro['contrasena'],
                $registro['permisos'],
                $id
            );
        } else {
            return false;
            //new self hace referencia a la clase actual, permitiendo crear una nueva instancia de la misma.
        }
    }

    public static function buscarPorNombre($nombre) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT nombre, contrasena, permisos, id FROM ' . self::TABLA . ' WHERE nombre LIKE :nombre ORDER BY nombre');
        $nombre = "%$nombre%";// el % es un comodín que permite buscar coincidencias parciales en SQL.
        $consulta->bindParam(':nombre', $nombre);
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public static function login($contrasena, $nombre) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . 
        ' WHERE contrasena = :contrasena AND nombre = :nombre');
        $consulta->bindParam(':contrasena', $contrasena);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->execute();
        $registro = $consulta->fetch();
        if ($registro) {
            return new self(
                $registro['nombre'],
                $registro['contrasena'],
                $registro['permisos'],
                $registro['id']
            );
        } else {
            return false;
    

        }
    }
    // Recuperar todos sirve para obtener todos los registros de la tabla.

    public static function recuperarTodos() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT id, nombre, contrasena, permisos FROM ' . self::TABLA . ' ORDER BY nombre');
        $consulta->execute();
        return $consulta->fetchAll();
    }
// sirve para eliminar un registro de la base de datos basado en su ID.
    public static function borrarRegistro($id) {
        try {
            $conexion = new Conexion();
            $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id = ?');
            $consulta->bindParam(1, $id);
            $consulta->execute();
        } catch (PDOException $e) {//el catch captura excepciones específicas, en este caso PDOException, 
                                    // que son errores relacionados con la base de datos.
            echo $e->getMessage();

        }
    }
}
?>
