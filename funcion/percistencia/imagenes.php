<?php
require_once 'Conexion.php';

class Imagenes {
    private $id;
    private $id_remate;
    private $id_lote;
    private $foto;
    private $prioridad;

    const TABLA = 'imagenes';

    // Getters
    public function getId() { return $this->id; }
    public function getIdRemate() { return $this->id_remate; }
    public function getIdLote() { return $this->id_lote; }
    public function getFoto() { return $this->foto; }
    public function getPrioridad() { return $this->prioridad; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setIdRemate($id_remate) { $this->id_remate = $id_remate; }
    public function setIdLote($id_lote) { $this->id_lote = $id_lote; }
    public function setFoto($foto) { $this->foto = $foto; }
    public function setPrioridad($prioridad) { $this->prioridad = $prioridad; }

    // Constructor
    public function __construct($id_remate, $id_lote, $foto, $prioridad, $id = null) {
        $this->id_remate = $id_remate;
        $this->id_lote = $id_lote;
        $this->foto = $foto;
        $this->prioridad = $prioridad;
        $this->id = $id;
    }

    // Guardar (Insertar o Modificar)
    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET 
                id_remate = :id_remate, 
                id_lote = :id_lote, 
                foto = :foto, 
                prioridad = :prioridad 
                WHERE id = :id');
            $consulta->bindParam(':id_remate', $this->id_remate);
            $consulta->bindParam(':id_lote', $this->id_lote);
            $consulta->bindParam(':foto', $this->foto);
            $consulta->bindParam(':prioridad', $this->prioridad);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' 
                (id_remate, id_lote, foto, prioridad) 
                VALUES (:id_remate, :id_lote, :foto, :prioridad)');
            $consulta->bindParam(':id_remate', $this->id_remate);
            $consulta->bindParam(':id_lote', $this->id_lote);
            $consulta->bindParam(':foto', $this->foto);
            $consulta->bindParam(':prioridad', $this->prioridad);
            $consulta->execute();
            $this->id = $conexion->lastInsertId();
        }
        $conexion = null;
    }

    // Eliminar
    public function eliminar() {
        if ($this->id) {
            $conexion = new Conexion();
            $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id = :id');
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
            $conexion = null;
        }
    }

    // Buscar por ID
    public static function buscarPorId($id) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE id = :id');
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        $registro = $consulta->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registro ? new self($registro['id_remate'], $registro['id_lote'], $registro['foto'], $registro['prioridad'], $registro['id']) : null;
    }

    // Listar todas las imágenes de un lote
    public static function listarPorLote($id_lote) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE id_lote = :id_lote ORDER BY prioridad ASC');
        $consulta->bindParam(':id_lote', $id_lote);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }
    // Recuperar objetos Imagen por lote
public static function recuperarPorLote($id_lote) {
    $conexion = new Conexion();
    $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE id_lote = :id_lote ORDER BY prioridad ASC');
    $consulta->bindParam(':id_lote', $id_lote);
    $consulta->execute();
    $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $conexion = null;

    $imagenes = [];
    foreach ($registros as $registro) {
        $imagenes[] = new self(
            $registro['id_remate'],
            $registro['id_lote'],
            $registro['foto'],
            $registro['prioridad'],
            $registro['id']
        );
    }
    return $imagenes;
}
}
?>
