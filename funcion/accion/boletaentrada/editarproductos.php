<?php
session_start();
require_once '../../percistencia/objetos.php';

header('Content-Type: application/json'); // siempre JSON


try {
   $objeto = $_POST['objetos'] ??[];
   $id_boleta = $_POST[''] ??[];

    // Capturar datos enviados
    $id         = $_POST['id'] ?? 0;
    $id_boleta = $_POST['id_boleta'] ?? 0;
    $imagenes   = $_POST['imagenes'] ?? '';
    $nombre      = $_POST['nombre'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $valor_esperado = $_POST['valor_esperado'] ?? '';


   
        // Crear nueva boleta
        $objeto = new Objetos(0, $cantidad, $nombre, $descripcion, $valor_esperado);
        $objeto->guardar();
        
        var_dump($_POST, $_FILES);

        if ($objeto->getId()) {
            echo json_encode(['ok' => true, 'id' => $objeto->getId()]);
            exit;
        } else {
            echo json_encode(['ok' => false, 'error' => 'Error al crear la boleta']);
            exit;
        }
    }
    catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
  