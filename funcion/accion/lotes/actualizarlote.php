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

$id         = !empty($_POST['id']) ? intval($_POST['id']) : 0;
$id_remate  = !empty($_POST['id_remate']) ? intval($_POST['id_remate']) : null;
$numero     = trim($_POST['numero'] ?? '');
$serie      = trim($_POST['serie'] ?? '');
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



    // Procesar múltiples imágenes subidas
    $prioridadSeleccionada = isset($_POST['prioridad']) ? intval($_POST['prioridad']) : -1;
    $grupoId = uniqid('grp_', true);

    if (!empty($_FILES['foto']['name'][0])) {
        foreach ($_FILES['foto']['name'] as $index => $fileName) {
$nombre_limpio = str_replace(' ', '_', basename($fileName));
$nombre_archivo = uniqid('img_', true) . "_" . $nombre_limpio;

$carpeta = realpath(__DIR__ . "/../boletaentrada/uploads");

if ($carpeta === false) {
    die("ERROR: La carpeta de destino no existe");
}

$ruta_destino = $carpeta . DIRECTORY_SEPARATOR . $nombre_archivo;

if (!move_uploaded_file($archivo_tmp, $ruta_destino)) {
    die("ERROR moviendo archivo: " . $ruta_destino);
}

$base_datos = "uploads/" . $nombre_archivo;


$archivo_tmp = $_FILES['foto']['tmp_name'][$index];

if (move_uploaded_file($archivo_tmp, $ruta_destino)) {
    $prioridad = ($index === $prioridadSeleccionada) ? 1 : 0;

    $insertarFoto = $db->prepare("
        INSERT INTO imagenes (id_remate, id_lote, foto, prioridad) 
        VALUES (:id_remate, :id_lote, :foto, :prioridad)
    ");
    $insertarFoto->bindValue(':id_remate', $id_remate, PDO::PARAM_INT);
    $insertarFoto->bindValue(':id_lote', $loteId, PDO::PARAM_INT);
    $insertarFoto->bindValue(':foto', $base_datos, PDO::PARAM_STR);
    $insertarFoto->bindValue(':prioridad', $prioridad, PDO::PARAM_INT);
    $insertarFoto->execute();
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
<script>
    function ruta_uploads() {
    return __DIR__ . "/../boletaentrada/uploads/";
}
function url_uploads($archivo) {
    return "/remate/funcion/accion/boletaentrada/uploads/" . $archivo;
}
</script>