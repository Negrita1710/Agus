<?php
    //require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
    // Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
    require_once '../percistencia/usuarios.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $permisos = $_POST['permisos'] ?? '';

        if ($id) {
            // Actualizar usuario existente
            $usuario = Usuarios::buscarPorId($id);
            if ($usuario) {
                $usuario->setNombre($nombre);
                $usuario->setContrasena($contrasena);
                $usuario->setPermisos($permisos);
                $usuario->guardar();
                echo "Usuario actualizado correctamente.";
            } else {
                echo "Usuario no encontrado.";
            }
        } else {
            // Crear nuevo usuario
            $usuario = new Usuarios($nombre, $contrasena, $permisos);
            $usuario->guardar();
            echo "Usuario creado correctamente.";
        echo  '<a href="../../index.php">Volver al inicio</a>';
        }
    } else {
        echo "Método no permitido.";
    }
?>