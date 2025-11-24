<?php
require_once '../funcion/accion/persistencia/Conexion.php';

if($_SERVER['REQUEST_METHOD'] !=='POST'){
    echo "Método no permitido";
    exit;
}
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) { echo json_encode(['ok' => false, 'error' => 'ID inválido']); exit; }
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['ok' => false, 'error' => 'Error al subir la imagen']);
    exit;
}
// finfo (fileinfo) para obtener el tipo MIME del archivo subido 
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
finfo_close($finfo);
// $allowed contiene los tipos MIME permitidos
$allowed = ['image/jpeg', 'image/png'];
if (!in_array($mime_type, $allowed)) {
    echo json_encode(['ok' => false, 'error' => 'Tipo de archivo no permitido']);
    exit;
}
$imagen_data = file_get_contents($_FILES['imagen']['tmp_name']);
try {
    $bd = new Conexion();
    $stmt = $bd->prepare('UPDATE objetos SET foto = ?, foto_mime = ? WHERE id = ?');
    $stmt->bindParam(':foto', $imagen_data, PDO::PARAM_LOB);
    $stmt->bindParam(':mime', $mime_type);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>