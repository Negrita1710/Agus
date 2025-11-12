<?php
    //require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
    // Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
    //__DIR__ es una constante mágica en PHP que devuelve el directorio del archivo actual.
    require_once __DIR__ . '/funcion/percistencia/clientes.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? ''; 
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $documento = $_POST['documento'] ?? '';
        $tipo_documento = $_POST['tipo_documento'] ?? '';
        

        $clientes = new Clientes($nombre, $email, $apellido, $direccion, $telefono, $documento, $tipo_documento, $id);

        $clientes->guardar();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Guardar cliente</title>
    </head>
        <body>
            <div id="inresultado">


                    <h2>Formulario de Cliente</h2>
                <form method="post" action="funcion/accion/actualizarclientes.php">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required><br><br>

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required><br><br>

                    <label for="apellido">Apellido:</label>
                    <input type="apellido" name="apellido" id="apellido" required><br><br>

                    <label for="direccion">Direccion:</label>
                    <input type="direccion" name="direccion" id="direccion" required><br><br>

                    <label for="telefono">Telefono:</label>
                    <input type="telefono" name="telefono" id="telefono" required><br><br>

                    <label for="documento">Documento:</label>
                    <input type="documento" name="documento" id="documento" required><br><br>

                    <label for="tipo_documento">Tipo de Documento:</label>
                    <input type="tipo_documento" name="tipo_documento" id="tipo_documento" required><br><br>
            
                </form>
            </div>
            <button type="submit" >Guardar cliente</button>
        </body>
</html>