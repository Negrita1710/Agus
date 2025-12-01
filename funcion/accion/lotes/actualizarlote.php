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
    if (!empty($_FILES['foto_objeto'])) {
        foreach ($_FILES['foto_objeto']['tmp_name'] as $id_obj => $tmp_names) {
            foreach ($tmp_names as $index => $tmp_name) {
                if ($_FILES['foto_objeto']['error'][$id_obj][$index] === UPLOAD_ERR_OK) {
                    // Obtener el nombre del objeto
                    $objeto = Objetos::buscarPorId($id_obj);
                    if ($objeto) {
                        $nombre = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($objeto->getNombre()));
                        // Generar nombre: timestamp_nombre.jpeg
                        $nombre_archivo = time() . '_' . $nombre . '.jpeg';
                        $ruta_destino = __DIR__ . "/../boletaentrada/uploads/" . $nombre_archivo;

                        if (move_uploaded_file($tmp_name, $ruta_destino)) {
                            $sqlImg = "UPDATE objetos SET foto = :nombre_archivo WHERE id = :objeto_id";
                            $stmtImg = $db->prepare($sqlImg);
                            $stmtImg->bindValue(':objeto_id', intval($id_obj), PDO::PARAM_INT);
                            $stmtImg->bindValue(':nombre_archivo', $nombre_archivo, PDO::PARAM_STR);
                            if (!$stmtImg->execute()) {
                                error_log("Error updating foto for object $id_obj: " . print_r($stmtImg->errorInfo(), true));
                            }
                        } else {
                            error_log("Error moving uploaded file for object $id_obj");
                        }
                    }
                }
            }
        }
    }

    $db->commit();

    echo json_encode(['ok' => true, 'id' => $loteId, 'message' => 'Lote guardado correctamente con imágenes.']);
    exit;
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
?>
