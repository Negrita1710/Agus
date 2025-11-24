<?php
session_start();
require_once '../../percistencia/objetos.php';

header('Content-Type: application/json');

try {
    $id          = $_POST['id'] ?? 0;
    $id_boleta   = $_POST['id_boleta'] ?? null;
    $nombre      = $_POST['nombre'] ?? '';
    $cantidad    = (int)($_POST['cantidad'] ?? 0);
    $descripcion = $_POST['descripcion'] ?? '';
    $valor_esperado = $_POST['valor_esperado'] ?? null;

    if (!$id_boleta) {
        throw new Exception("Falta id_boleta en el formulario");
    }

    $objeto = new Objetos($id_boleta, $cantidad, $nombre, $descripcion, $valor_esperado, $id);
    $objeto->guardar();

    echo json_encode(['ok' => true, 'id' => $objeto->getId()]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage(), 'post' => $_POST]);
}
  