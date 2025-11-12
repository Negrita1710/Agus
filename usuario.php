<?php
    //require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
    // Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
    //__DIR__ es una constante mágica en PHP que devuelve el directorio del archivo actual.
    require_once __DIR__ . '/funcion/percistencia/usuarios.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        $usuario = new Usuarios();
        $resultado = $usuario->guardarUsuario($nombre, $email, $contrasena);

        if ($resultado) {
    echo "<p>Usuario guardado correctamente.</p>";
        } else {
            echo "<p>Error al guardar el usuario.</p>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="funcion/css/style.css">
    <title>Registro</title>
</head>
<body>
    <div class= "registro-container">
        <h2>Registrarse</h2>
        <form method="post" action="funcion/accion/actualizarusuario.php">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required><br><br>

            <p>¿Ya tienes una cuenta? <a href="index.php">Ingresa aquí</a></p>

            <button type="submit">Guardar usuario</button>

        </form>
    </div>
</body>
</html>