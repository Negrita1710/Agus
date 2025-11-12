<?php
require_once 'Conexion.php';

class HistorialEventos {
    private $id;
    private $id_usuario;
    private $descripcion;

    const TABLA = 'historial_eventos';

    // Getters
    public function getId() { return $this->id; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getDescripcion() { return $this->descripcion; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    // Constructor
    public function __construct($id_usuario, $descripcion, $id = null) {
        $this->id_usuario = $id_usuario;
        $this->descripcion = $descripcion;
        $this->id = $id;
    }

    // Guardar (Insertar o Modificar)
    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET 
                id_usuario = :id_usuario, 
                descripcion = :descripcion 
                WHERE id = :id');
            $consulta->bindParam(':id_usuario', $this->id_usuario);
            $consulta->bindParam(':descripcion', $this->descripcion);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' 
                (id_usuario, descripcion) 
                VALUES (:id_usuario, :descripcion)');
            $consulta->bindParam(':id_usuario', $this->id_usuario);
            $consulta->bindParam(':descripcion', $this->descripcion);
            $consulta->execute();
            $this->id = $conexion->lastInsertId();
        }
        $conexion = null;
    }
}
?>
