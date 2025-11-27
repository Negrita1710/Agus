<?php
// filepath: c:\xampp\htdocs\remate\funcion\accion\objetos\subirimagen.php
require_once '../../percistencia/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'error'=>'Method not allowed']);
  exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
  echo json_encode(['ok'=>false,'error'=>'id missing']);
  exit;
}

if (!isset($_FILES['imagen']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['ok'=>false,'error'=>'No file uploaded']);
  exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['foto']['tmp_name']);
finfo_close($finfo);

$allowed = ['image/jpeg','image/png','image/gif'];
if (!in_array($mime, $allowed)) {
  echo json_encode(['ok'=>false,'error'=>'Tipo de archivo no permitido']);
  exit;
}

$ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
$nombre = bin2hex(random_bytes(8)) . '.' . $ext;
$destDir = __DIR__ . '/../../uploads/objetos/';
if (!is_dir($destDir)) mkdir($destDir, 0755, true);
$dest = $destDir . $nombre;

if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
  echo json_encode(['ok'=>false,'error'=>'Error al mover archivo']);
  exit;
}

try {
  $db = new Conexion();
  $stmt = $db->prepare('UPDATE objetos SET foto = :foto WHERE id = :id');
  $stmt->bindValue(':foto', $nombre);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  
  echo json_encode(['ok'=>true,'message'=>'Imagen guardada correctamente','foto'=>$nombre]);
} catch (Exception $e) {
  // si la BD falla, eliminar el archivo subido
  @unlink($dest);
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}