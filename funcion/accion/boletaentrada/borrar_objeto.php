<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    require_once '../../percistencia/objetos.php';
    require_once '../../percistencia/historial.php';

    $id = $_POST['id'] ?? null;

    if ($id) { 
        $objeto = Objetos::borrarRegistro($id);

        
        try {
        $historial = new HistorialEventos($_SESSION['usuario_id'], "Eliminó el objeto con ID $id");
        $historial->guardar();
             echo json_encode([
                        "ok" => true
                    ]);
    } catch (PDOException $e) {
        echo "Error al guardar en historial: " . $e->getMessage();
    }

        }
    else {
        echo "ID de objeto no proporcionado.";
    }

    exit;
?>
