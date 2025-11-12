<?php
    session_start();
?>
<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="funcion/css/style.css">
</head>
<body>
    
    <?php
        if (isset($_SESSION['mensaje'])) {
            echo '<p style="color:red;">' . $_SESSION['mensaje'] . '</p>';
            unset($_SESSION['mensaje']);
        }
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            header('Location: work/index.php');
        }
    ?>
    <form action="funcion/accion/login.php" method="post">
        <div class="inicio-container">
            <h2>Inicio sesion</h2><br>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required><br><br>
        
            <p>¿No tienes una cuenta? <a href="usuario.php">Regístrate aquí</a></p>

            <a href=work/index.php><button type="submit">Ingresar</button></a>
        
    </form>
        </div>
    
</body>
</html>