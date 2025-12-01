<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_objeto = intval($_POST['id_objeto']); // el ID del objeto
    $nombre_objeto = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($_POST['nombre_objeto'])); 
    // sanitiza el nombre para evitar caracteres raros

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['foto']['tmp_name'];
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        // Generar nombre: id_timestamp_nombre.extension
        $nombre_archivo = $id_objeto . "_" . time() . "_" . $nombre_objeto . "." . strtolower($extension);
        $ruta_destino = __DIR__ . "/uploads/" . $nombre_archivo;

        if (move_uploaded_file($tmp_name, $ruta_destino)) {
            // Guardar en la tabla imagenes
            $sql = "INSERT INTO foto (objeto_id, nombre_archivo) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $id_objeto, $nombre_archivo);
            $stmt->execute();

            echo "Imagen subida como: " . $nombre_archivo;
        }
    }
}
?>
