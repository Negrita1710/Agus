<?php
    header('Content-Type: application/json');
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    require_once '../../percistencia/boletaentrada.php';
    require_once '../../percistencia/objetos.php';

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $moneda = $_POST['moneda'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $id_cliente = $_POST['id_cliente'] ?? '';

            if ($id > 0) {
                // Actualizar boleta existente
                $boleta = BoletaEntrada::buscarPorId($id);
                if ($boleta) {
                    $boleta->setMoneda($moneda);
                    $boleta->setFecha($fecha);
                    $boleta->setIdCliente($id_cliente);
                    $boleta->guardar();
                    /*
                    if (isset($_POST['objetos']) && is_array($_POST['objetos'])) {
                        guardarProductos($_POST['objetos'], $boleta->getId());
                    }
                    echo json_encode(["ok" => true]);
                */
                } else {
                    echo json_encode(["ok" => false, "error" => "Boleta no encontrada"]);
                }
                    
            } else {
                $boleta = new BoletaEntrada($moneda, $fecha, $id_cliente, null);
                $boleta->guardar();
                /*
                if ($boleta->getId() != null) {
                    if (isset($_POST['objetos']) && is_array($_POST['objetos'])) {
                        guardarProductos($_POST['objetos'], $boleta->getId());
                    }
                    echo json_encode(["ok" => true]);
                } else {
                    echo json_encode(["ok" => false, "error" => "Error al guardar boleta"]);
                }
                */
            }
        } else {
            echo json_encode(["ok" => false, "error" => "Método no permitido"]);
        }
    } catch (Exception $e) {
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }

    function guardarProductos($productos, $id_boleta) {
        // Primero, eliminar productos existentes para esta boleta
        Objetos::eliminarPorBoleta($id_boleta);

        // Luego, insertar los nuevos productos
        foreach ($productos as $index => $producto) {
            if (!empty($producto['nombre']) && !empty($producto['cantidad'])) {
                $foto_path = null;
                if (isset($_FILES['productos']['name'][$index]['foto']) && $_FILES['productos']['error'][$index]['foto'] == UPLOAD_ERR_OK) {
                    $foto_tmp = $_FILES['productos']['tmp_name'][$index]['foto'];
                    $foto_name = basename($_FILES['productos']['name'][$index]['foto']);
                    $foto_path = 'uploads/' . time() . '_' . $foto_name;
                    if (!move_uploaded_file($foto_tmp, $foto_path)) {
                        throw new Exception("Error al subir la imagen");
                    }
                }

                $objeto = new Objetos(
                    $id_boleta,
                    $producto['cantidad'],
                    $producto['nombre'],
                    $producto['descripcion'],
                    $producto['valor_esperado'],
                    null, // Siempre insertar como nuevo
                    $producto['id_estadoRemate'] ?? 0,
                    $producto['estadoVenta'] ?? 1,
                    $foto_path
                );
                $objeto->guardar();
            }
        }
    }
?>
