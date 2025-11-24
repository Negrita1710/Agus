<?php
    //require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
    // Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
    //__DIR__ es una constante mágica en PHP que devuelve el directorio del archivo actual.
    require_once __DIR__ . '/funcion/percistencia/boletaentrada.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? 0;
        $moneda = $_POST['moneda'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $id_cliente = $_POST['id_cliente'] ?? '';
    

        $objetos = new Objetos($id, $moneda, $fecha, $id_cliente, $valor_esperado);

        $objetos->guardar();
    }
?>

<!DOCTYPE html>
<html>
    <head>  
        <title>Guardar producto</title>
    </head>
<body>
    <div id="inresultado">
    <h2>Formulario de producto</h2>
    <form method="post" action="funcion/accion/boletaentrada/actualizarboletadeentrada.php">
        <label for="nombre">ID:</label>
        <input type="text" name="nombre" id="nombre" required><br><br>

        <label for="descripcion">Moneda:</label>
        <input type="descripcion" name="descripcion" id="descripcion" required><br><br>

        <label for="estado">Fecha:</label>
        <input type="estado" name="estado" id="estado" required><br><br>

        <label for="documento">ID cliente:</label>
        <input type="documento" name="documento" id="documento" required><br><br>

    
       
       
    </form>
    </div>
     <button type="submit" >Guardar boleta</button>
</body>
</html>