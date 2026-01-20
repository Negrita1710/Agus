<?php
session_start();
require_once '../../percistencia/boletaentrada.php';

header('Content-Type: application/json'); // siempre JSON
//holis, viendo si cambiando el coodigo de casa al disco C medio que arregla el problema d las imagenes
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
            // Si hay datos de producto, guardarlos
            if (isset($_POST['nombre']) && isset($_POST['cantidad']) && isset($_POST['descripcion']) && isset($_POST['valor_esperado'])) {
                require_once '../../percistencia/objetos.php';
                $objeto = new Objetos(
                    $boletaentrada->getId(), // id_boleta
                    $_POST['cantidad'],
                    $_POST['nombre'],
                    $_POST['descripcion'],
                    $_POST['valor_esperado']
                );
                // Manejar la imagen si se subió
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                    $uploadDir ='../../../uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $foto = time() . '_' . basename($_FILES['foto']['name']);
                    $uploadFile = $uploadDir . $foto;
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                        $objeto->setFoto($foto);
                    }
                }
                $objeto->guardar();
            }
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
  