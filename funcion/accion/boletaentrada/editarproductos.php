<?php
require_once ' ../../percistencia/objetos.php';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            $id = $input['id'] ?? 0;


            $id = $input['id'] ?? 0;


            $nombre = $input['nombre'] ?? '';
            $cantidad = $input['cantidad'] ?? '';
            $descripcion = $input['descripcion'] ?? '';
            $valor_esperado = $input['valor_esperado'] ??'';
            


            if ($id > 0) {
                // Actualizar productos existentes
                $objetos = Objetos::buscarPorId($id);
                if ($objetos) {
               
                    $objetos->setId($id);
                    $objetos->setNombre($nombre);
                    $objetos->setCantidad($cantidad);
                    $objetos->setDescripcion($descripcion);
                    $objetos->setValorEsperado($valor_esperado);
                    $objetos->guardar();
                }else {
                    "ID no encontrado para actualizar.";
            
        }
    }
}
?>