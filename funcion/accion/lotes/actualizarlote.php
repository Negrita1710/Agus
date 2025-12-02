<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once '../../percistencia/Conexion.php';
require_once '../../percistencia/lote.php';
require_once '../../percistencia/objetos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$id = !empty($_POST['id']) ? intval($_POST['id']) : 0;
$id_remate = !empty($_POST['id_remate']) ? intval($_POST['id_remate']) : null;
$numero = trim($_POST['numero'] ?? '');
$serie = trim($_POST['serie'] ?? '');
$id_objetos = isset($_POST['id_objeto']) ? (array) $_POST['id_objeto'] : [];

try {
    // Crear o actualizar lote
    if ($id > 0) {
        $lote = Lote::buscarPorId($id);
        if (!$lote) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'Lote no encontrado']);
            exit;
        }
        $lote->setNumero($numero);
        $lote->setSerie($serie);
        $lote->setIdRemate($id_remate);
        $lote->guardar();
        $loteId = $lote->getId();
    } else {
        $lote = new Lote($id_remate, $numero, $serie, null);
        $lote->guardar();
        $loteId = $lote->getId();
    }

    // Actualizar relaciones en la tabla junction
    $db = new Conexion();
    $db->beginTransaction();

    $del = $db->prepare('DELETE FROM lote_objetos WHERE id_lote = :id_lote');
    $del->bindValue(':id_lote', $loteId, PDO::PARAM_INT);
    $del->execute();

    if (!empty($id_objetos)) {
        $ins = $db->prepare('INSERT INTO lote_objetos (id_lote, id_objeto) VALUES (:id_lote, :id_objeto)');
        foreach ($id_objetos as $id_obj) {
            $ins->bindValue(':id_lote', $loteId, PDO::PARAM_INT);
            $ins->bindValue(':id_objeto', intval($id_obj), PDO::PARAM_INT);
            $ins->execute();
        }
    }

    // Procesar imágenes subidas
    if (!empty($_FILES['foto'])) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            $foto = $_POST ['foto'];
            $nombre_archivo = ['foto']['name'];
            $archivo = $_FILES['foto']['tmp_name'];

            $ruta_destino = "../boletaentrada/uploads/" . $nombre_archivo;
            $base_datos = "uploads/" . $nombre_archivo;

            move_uploaded_file($archivo, $ruta_destino);

            $instertar = "INSERT INTO objetos (foto) VALUES ('$base_datos')";

            if (mysqli_query($conexion, $instertar)) {
                echo "Imagen subida y ruta guardada en la base de datos correctamente.";
            } else {
                echo "Error al guardar la ruta en la base de datos: " . mysqli_error($conexion);
            }
        
}  
    }

    $db->commit();

    echo json_encode(['ok' => true, 'lote_id' => $loteId]);
} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error al actualizar el lote: ' . $e->getMessage()]);
}


?>
