<?php
session_start();
require_once '../../percistencia/boletaentrada.php';

header('Content-Type: application/json'); // siempre JSON

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
        exit;
    }

    // Capturar datos enviados
    $id         = $_POST['id'] ?? 0;
    $moneda     = $_POST['moneda'] ?? '';
    $fecha      = $_POST['fecha'] ?? '';
    $id_cliente = $_POST['id_cliente'] ?? '';

    if ($id > 0) {
        // Actualizar boleta existente
        $boletaentrada = BoletaEntrada::buscarPorId($id);
        if ($boletaentrada) {
            $boletaentrada->setMoneda($moneda);
            $boletaentrada->setFecha($fecha);
            $boletaentrada->setIdCliente($id_cliente);
            $boletaentrada->guardar();

            echo json_encode(['ok' => true, 'id' => $boletaentrada->getId()]);
            exit;
        } else {
            echo json_encode(['ok' => false, 'error' => 'Boleta no encontrada']);
            exit;
        }
    } else {
        // Crear nueva boleta
        $boletaentrada = new BoletaEntrada($moneda, $fecha, $id_cliente, null);
        $boletaentrada->guardar();

        if ($boletaentrada->getId()) {
            echo json_encode(['ok' => true, 'id' => $boletaentrada->getId()]);
            exit;
        } else {
            echo json_encode(['ok' => false, 'error' => 'Error al crear la boleta']);
            exit;
        }
    }
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
  