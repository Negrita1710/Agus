<?php
    session_start();
    require_once '../percistencia/usuarios.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $usuario = Usuarios::login($contrasena, $nombre);
        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nombre'] = $usuario->getNombre();
            $_SESSION['usuario_permisos'] = $usuario->getPermisos();
            $_SESSION['login'] = true;
            

            header('Location: ../../work/index.php');
        } else {
            $_SESSION['mensaje'] = "Nombre o contraseña incorrectos.";
            header('Location: ../../index.php');
        }


    }
?>