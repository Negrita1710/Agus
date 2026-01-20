<?php
session_start();
require_once '../../percistencia/objetos.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$id_boleta = intval($_POST['id_boleta'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$cantidad = intval($_POST['cantidad'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');
$valor_esperado = floatval($_POST['valor_esperado'] ?? 0);

if (!$id_boleta || !$nombre || !$cantidad || !$valor_esperado) {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

$foto_path = '';

if (!empty($_FILES['foto']['name'])) {
    $upload_dir =  '../../../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_name = time() . '_' . basename($_FILES['foto']['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        $foto_path = '' . $file_name;
    } else {
        echo json_encode(['ok' => false, 'error' => 'Error al subir la imagen']);
        exit;
    }
}

try {
    $objeto = new Objetos($id_boleta, $cantidad, $nombre, $descripcion, $valor_esperado, null, '', '', $foto_path);
    $objeto->guardar();

    echo json_encode(['ok' => true, 'id' => $objeto->getId()]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>
