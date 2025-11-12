<?php
//require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
// Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.

require_once 'Conexion.php';

class BoletaPista {
    private $id;
    private $fecha;
    private $id_cliente;
    private $id_lote;
    private $cantidad;

//la constante TABLA define el nombre de la tabla en la base de datos asociada a esta clase.
    const TABLA = 'boleta_pista';

    // Getters son funciones que devuelven el valor de una propiedad privada de una clase.
    public function getId() { return $this->id; }
    public function getFecha() { return $this->fecha; }
    public function getIdCliente() { return $this->id_cliente; }
    public function getIdLote() { return $this->id_lote; }
    public function getCantidad() { return $this->cantidad; }

    // Setters son funciones que permiten asignar un valor a una propiedad privada de una clase.
    public function setId($id) { $this->id = $id; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setIdCliente($id_cliente) { $this->id_cliente = $id_cliente; }
    public function setIdLote($id_lote) { $this->id_lote = $id_lote; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }

    // Constructor es una función especial que se llama automáticamente cuando se crea un objeto de la clase.
    public function __construct($fecha, $id_cliente, $id_lote, $cantidad, $id = null) {
        $this->fecha = $fecha;
        $this->id_cliente = $id_cliente;
        $this->id_lote = $id_lote;
        $this->cantidad = $cantidad;
        $this->id = $id;
    }

    // Guardar (Insertar o Modificar)
    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            //self::TABLA hace referencia a la constante TABLA definida en la clase actual.
            $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET 
                fecha = :fecha, 
                id_cliente = :id_cliente, 
                id_lote = :id_lote, 
                cantidad = :cantidad 
                WHERE id = :id');
                //bindparam se utiliza para vincular un valor a un parámetro en una consulta preparada,
                //  mejorando la seguridad y previniendo inyecciones SQL.
            $consulta->bindParam(':fecha', $this->fecha);
            $consulta->bindParam(':id_cliente', $this->id_cliente);
            $consulta->bindParam(':id_lote', $this->id_lote);
            $consulta->bindParam(':cantidad', $this->cantidad);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' 
                (fecha, id_cliente, id_lote, cantidad) 
                VALUES (:fecha, :id_cliente, :id_lote, :cantidad)');
            $consulta->bindParam(':fecha', $this->fecha);
            $consulta->bindParam(':id_cliente', $this->id_cliente);
            $consulta->bindParam(':id_lote', $this->id_lote);
            $consulta->bindParam(':cantidad', $this->cantidad);
            $consulta->execute();
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
                $registro['fecha'],
                $registro['id_cliente'],
                $registro['id_lote'],
                $registro['cantidad'],
                $registro['id']
            );
        } else {
            return false;
        }
    }

    // Recuperar todos sirve para obtener todos los registros de la tabla.
    public static function recuperarTodos() {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' ORDER BY fecha DESC');
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
