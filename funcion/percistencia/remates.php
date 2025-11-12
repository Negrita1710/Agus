<?php

require_once 'Conexion.php';

class Remates {
    private $id;
    private $fecha;
    private $moneda;
    private $sena;
    private $com_comprador;
    private $com_vendedor;
    private $imp_municipal;
    private $observaciones;
    private $estadoRemate;

    const TABLA = 'remates';

    public function getId() { return $this->id; }
    public function getFecha() { return $this->fecha; }
    public function getMoneda() { return $this->moneda; }
    public function getsena() { return $this->sena; }
    public function getComComprador() { return $this->com_comprador; }
    public function getComVendedor() { return $this->com_vendedor; }
    public function getImpMunicipal() { return $this->imp_municipal; }
    public function getObservaciones() { return $this->observaciones; }
    public function getEstadoRemate() { return $this->estadoRemate; }

    public function setId($id) { $this->id = $id; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setMoneda($moneda) { $this->moneda = $moneda; }
    public function setsena($sena) { $this->sena = $sena; }
    public function setComComprador($com_comprador) { $this->com_comprador = $com_comprador; }
    public function setComVendedor($com_vendedor) { $this->com_vendedor = $com_vendedor; }
    public function setImpMunicipal($imp_municipal) { $this->imp_municipal = $imp_municipal; }
    public function setObservaciones($observaciones) { $this->observaciones = $observaciones; }
    public function setEstadoRemate($estadoRemate) { $this->estadoRemate = $estadoRemate; }

    public function __construct($fecha, $moneda, $sena, $com_comprador, $com_vendedor, $imp_municipal, $observaciones, $id = null, $estadoRemate = null) {
        $this->fecha = $fecha;
        $this->moneda = $moneda;
        $this->sena = $sena;
        $this->com_comprador = $com_comprador;
        $this->com_vendedor = $com_vendedor;
        $this->imp_municipal = $imp_municipal;
        $this->observaciones = $observaciones;
        $this->id = $id;
        $this->estadoRemate = $estadoRemate;
    }

    public function guardar() {
        $conexion = new Conexion();
        if ($this->id) {
            $consulta = $conexion->prepare(
                'UPDATE ' . self::TABLA . ' SET fecha = :fecha, moneda = :moneda, sena = :sena, com_comprador = :com_comprador, com_vendedor = :com_vendedor, imp_municipal = :imp_municipal, observaciones = :observaciones, estadoRemate = :estadoRemate WHERE id = :id'
            );
            $consulta->bindParam(':fecha', $this->fecha);
            $consulta->bindParam(':moneda', $this->moneda);
            $consulta->bindParam(':sena', $this->sena);
            $consulta->bindParam(':com_comprador', $this->com_comprador);
            $consulta->bindParam(':com_vendedor', $this->com_vendedor);
            $consulta->bindParam(':imp_municipal', $this->imp_municipal);
            $consulta->bindParam(':observaciones', $this->observaciones);
            $consulta->bindParam(':estadoRemate', $this->estadoRemate);
            $consulta->bindParam(':id', $this->id);
            $consulta->execute();
        } else {
            $consulta = $conexion->prepare(
                'INSERT INTO ' . self::TABLA . ' (fecha, moneda, sena, com_comprador, com_vendedor, imp_municipal, observaciones, estadoRemate) VALUES (:fecha, :moneda, :sena, :com_comprador, :com_vendedor, :imp_municipal, :observaciones, :estadoRemate)'
            );
            $consulta->bindParam(':fecha', $this->fecha);
            $consulta->bindParam(':moneda', $this->moneda);
            $consulta->bindParam(':sena', $this->sena);
            $consulta->bindParam(':com_comprador', $this->com_comprador);
            $consulta->bindParam(':com_vendedor', $this->com_vendedor);
            $consulta->bindParam(':imp_municipal', $this->imp_municipal);
            $consulta->bindParam(':observaciones', $this->observaciones);
            $consulta->bindParam(':estadoRemate', $this->estadoRemate);
            $consulta->execute();
            $this->id = $conexion->lastInsertId();
        }
        $conexion = null;
    }

    public static function buscarPorId($id) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE id = :id');
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        $registro = $consulta->fetch();
        if ($registro) {
            return new self(
                $registro['fecha'],
                $registro['moneda'],
                $registro['sena'],
                $registro['com_comprador'],
                $registro['com_vendedor'],
                $registro['imp_municipal'],
                $registro['observaciones'],
                $registro['id'],
                $registro['estadoRemate']
            );
        } else {
            return false;
        }
    }

    public static function buscarPor($valor = null) {
        $conexion = new Conexion();
        $sql = 'SELECT * FROM ' . self::TABLA;

        if (!empty($valor)) {
            $sql .= ' WHERE fecha LIKE :fecha OR moneda LIKE :moneda OR sena LIKE :sena OR com_comprador LIKE :com_comprador OR com_vendedor LIKE :com_vendedor OR imp_municipal LIKE :imp_municipal OR observaciones LIKE :observaciones OR estadoRemate LIKE :estadoRemate';
            $consulta = $conexion->prepare($sql);
            $valorLike = '%' . $valor . '%';
            $consulta->bindValue(':fecha', $valorLike);
            $consulta->bindValue(':moneda', $valorLike);
            $consulta->bindValue(':sena', $valorLike);
            $consulta->bindValue(':com_comprador', $valorLike);
            $consulta->bindValue(':com_vendedor', $valorLike);
            $consulta->bindValue(':imp_municipal', $valorLike);
            $consulta->bindValue(':observaciones', $valorLike);
            $consulta->bindValue(':estadoRemate', $valorLike);
        } else {
            $consulta = $conexion->prepare($sql);
        }

        $consulta->execute();
        return $consulta->fetchAll();
    }

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

    public static function recuperarConEstado() {
        $conexion = new Conexion();
        $sql = 'SELECT r.*,
                       COUNT(l.id) AS total_lotes,
                       SUM(CASE WHEN (l.venta_id IS NOT NULL AND l.venta_id <> 0) THEN 1 ELSE 0 END) AS vendidos,
                       GROUP_CONCAT(DISTINCT CASE WHEN (l.venta_id IS NOT NULL AND l.venta_id <> 0) THEN l.venta_id END) AS remitos_vendidos
                FROM ' . self::TABLA . ' r
                LEFT JOIN lote l ON l.id_remate = r.id
                GROUP BY r.id
                ORDER BY r.fecha DESC';
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
} // end class
?>