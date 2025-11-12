<?php
// ...existing code...
require_once '../../percistencia/remates.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

// leer campos
$id = $_POST['id'] ?? 0;
$fecha = $_POST['fecha'] ?? '';
$moneda = $_POST['moneda'] ?? '';
$sena = $_POST['sena'] ?? '';
$com_comprador = $_POST['com_comprador'] ?? '';
$com_vendedor = $_POST['com_vendedor'] ?? '';
$imp_municipal = $_POST['imp_municipal'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

header('Content-Type: application/json; charset=utf-8');

try {
    if ($id > 0) {
        $remates = Remates::buscarPorId($id);
        if (!$remates) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'Remate no encontrado']);
            exit;
        }
        $remates->setId($id);
        $remates->setFecha($fecha);
        $remates->setMoneda($moneda);
        $remates->setsena($sena);
        $remates->setComComprador($com_comprador);
        $remates->setComVendedor($com_vendedor);
        $remates->setImpMunicipal($imp_municipal);
        $remates->setObservaciones($observaciones);
        $remates->guardar();

        echo json_encode(['ok' => true, 'action' => 'updated', 'id' => $id, 'message' => 'Remate actualizado correctamente.']);
        exit;
    } else {
        $remates = new Remates($fecha, $moneda, $sena, $com_comprador, $com_vendedor, $imp_municipal, $observaciones);
        $remates->guardar();
        $newId = $remates->getId() ?? null;
        if ($newId) {
            echo json_encode(['ok' => true, 'action' => 'inserted', 'id' => $newId, 'message' => 'Remate creado correctamente.']);
            exit;
        }
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error al crear el remate.']);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
?>