<?php
require_once '../../percistencia/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405); exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['ok'=>false,'error'=>'No file uploaded']); exit;
}

$allowed = ['image/jpeg','image/png','image/gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
finfo_close($finfo);
if (!in_array($mime, $allowed)) {
  echo json_encode(['ok'=>false,'error'=>'Tipo de archivo no permitido']); exit;
}

$ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
$nombre = bin2hex(random_bytes(10)) . '.' . $ext;
$dest = __DIR__ . '/../../uploads/objetos/' . $nombre;
if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);

if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $dest)) {
  echo json_encode(['ok'=>false,'error'=>'Error al mover archivo']); exit;
}

// Leer el contenido del archivo como binario
$imagen_binaria = file_get_contents($dest);

// actualizar tabla objetos con imagen binaria
$db = new Conexion();
$stmt = $db->prepare('UPDATE objetos SET imagen = :imagen WHERE id = :id');
$stmt->bindValue(':imagen', $imagen_binaria, PDO::PARAM_LOB);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

echo json_encode(['ok'=>true,'imagen'=>$nombre]);
?>