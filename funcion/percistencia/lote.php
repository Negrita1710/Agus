<?php
//require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
// Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
require_once 'Conexion.php';

class Lote {
    private $id;
    private $id_remate;
    private $id_objeto;
    private $numero;
    private $serie;
//la constante TABLA define el nombre de la tabla en la base de datos asociada a esta clase.
    const TABLA = 'lote';
   // Getters son funciones que devuelven el valor de una propiedad privada de una clase.
    public function getId() { return $this->id; }
    public function getIdRemate() { return $this->id_remate; }
    public function getIdObjeto() { return $this->id_objeto; }
    public function getNumero() { return $this->numero; }
    public function getSerie() { return $this->serie; }
 // Setters son funciones que permiten asignar un valor a una propiedad privada de una clase.
    public function setId($id) { $this->id = $id; }
    public function setIdRemate($id_remate) { $this->id_remate = $id_remate; }
    public function setIdObjeto($id_objeto) { $this->id_objeto = $id_objeto; }
    public function setNumero($numero) { $this->numero = $numero; }
    public function setSerie($serie) { $this->serie = $serie; }

// Constructor es una función especial que se llama automáticamente cuando se crea un objeto de la clase.
    public function __construct($id_remate, $numero, $serie, $id = null) {
        $this->id_remate = $id_remate;
        $this->numero = $numero;
        $this->serie = $serie;
        $this->id = $id;
    }

    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            //self::TABLA hace referencia a la constante TABLA definida en la clase actual.
            $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET id_remate = :id_remate, id_objeto = :id_objeto, numero = :numero, serie = :serie WHERE id = :id');
           //bindparam se utiliza para vincular un valor a un parámetro en una consulta preparada, 
           // mejorando la seguridad y previniendo inyecciones SQL.
            $consulta->bindParam(':id_remate', $this->id_remate);
            $consulta->bindParam(':id_objeto', $this->id_objeto);
            $consulta->bindParam(':numero', $this->numero);
            $consulta->bindParam(':serie', $this->serie);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (id_remate, id_objeto, numero, serie) VALUES (:id_remate, :id_objeto, :numero, :serie)');
            $consulta->bindParam(':id_remate', $this->id_remate);
            $consulta->bindParam(':id_objeto', $this->id_objeto);
            $consulta->bindParam(':numero', $this->numero);
            $consulta->bindParam(':serie', $this->serie);
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
            // corregido: pasar parámetros en el orden que espera el constructor
            return new self($registro['id_remate'], $registro['numero'], $registro['serie'], $registro['id']);
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' ORDER BY numero');
        $consulta->execute();
        return $consulta->fetchAll();
    }

    // nuevo: recuperarDisponibles dentro de la clase
    public static function recuperarDisponibles() {
        $conexion = new Conexion();
        $sql = 'SELECT * FROM ' . self::TABLA . ' WHERE (id_remate IS NULL OR id_remate = 0) AND (venta_id IS NULL OR venta_id = 0) ORDER BY numero';
        $consulta = $conexion->prepare($sql);
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public static function borrarRegistro($id) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id = ?');
        $consulta->bindParam(1, $id);
        $consulta->execute();
  
    }
}
?>
