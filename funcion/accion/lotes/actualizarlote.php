<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once '../../percistencia/Conexion.php';
require_once '../../percistencia/lote.php';
require_once '../../percistencia/objetos.php';

// Aquí va tu conexión a la base de datos (PDO), si no está ya incluida
// Ejemplo: require_once '../config/db.php'; // Ajusta la ruta según tu estructura
// $db = new PDO(...); // Asumiendo que $db es tu objeto PDO

// Verificar si el formulario fue enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos necesarios para las imágenes (desde el formulario)
    $id_lote = intval($_POST['id_lote']);
    $id_remate = intval($_POST['id_remate']);
    $prioridadSeleccionada = isset($_POST['prioridad']) ? intval($_POST['prioridad']) : -1;
    
    // --- AQUÍ VA EL PEDAZO DE CÓDIGO PARA SUBIDA DE IMÁGENES ---
    // (Copia y pega el código que te di aquí)
    
    // Subida de imágenes
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/funcion/accion/boletaentrada/uploads/'; // Ruta absoluta
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Crear si no existe
    }
    
    if (!empty($_FILES['foto']['name'][0])) {
        foreach ($_FILES['foto']['name'] as $index => $fileName) {
            if ($_FILES['foto']['error'][$index] !== UPLOAD_ERR_OK) {
                echo "Error en archivo $fileName: " . $_FILES['foto']['error'][$index];
                continue;
            }
            
            $nombre_archivo = uniqid('img_', true) . "_" . basename($fileName);
            $ruta_destino = $uploadDir . $nombre_archivo;
            $base_datos = "uploads/" . $nombre_archivo;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'][$index], $ruta_destino)) {
                $prioridad = ($index === $prioridadSeleccionada) ? 1 : 0;
                try {
                    $insertarFoto = $db->prepare("INSERT INTO imagenes (id_remate, id_lote, foto, prioridad) VALUES (:id_remate, :id_lote, :foto, :prioridad)");
                    $insertarFoto->execute([
                        ':id_remate' => $id_remate,
                        ':id_lote' => $id_lote,
                        ':foto' => $base_datos,
                        ':prioridad' => $prioridad
                    ]);
                    echo "Imagen $fileName subida correctamente.";
                } catch (Exception $e) {
                    echo "Error al guardar imagen en DB: " . $e->getMessage();
                }
            } else {
                echo "Error al mover archivo $fileName.";
            }
        }
    } else {
        echo "No se subieron imágenes.";
    }
    
    // --- FIN DEL PEDAZO DE CÓDIGO ---
    
    // Opcional: Redirigir o mostrar mensaje de éxito/error
    // header('Location: ../pagina_de_exito.php'); // Ejemplo
} else {
    // Si no es POST, redirigir o mostrar error
    echo "Acceso no autorizado.";
}
?>
