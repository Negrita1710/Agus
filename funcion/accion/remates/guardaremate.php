<?php
    //require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
    // Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
    //__DIR__ es una constante mágica en PHP que devuelve el directorio del archivo actual.
    require_once __DIR__ . '/funcion/percistencia/obejto.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? 0;
        $fecha = $_POST['fecha'] ?? '';
        $moneda = $_POST['moneda'] ?? '';
        $sena = $_POST['sena'] ?? '';
        $com_comprador = $_POST['com_comprador'] ?? '';
        $com_vendedor = $_POST['com_vendedor'] ?? '';
        $imp_municipal = $_POST['imp_municipal'] ?? '';
        $observaciones = $_POST['observaciones'] ?? '';
        

        $remates = new Remates($fecha, $moneda, $sena, $com_comprador, $com_vendedor, $imp_municipal, $observaciones);

        $remates->guardar();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guardar remate</title>
</head>
<body>
    <h2>Formulario de remate</h2>
    <form method="post" action="funcion/accion/actualizaremate.php">
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required><br><br>

        <label for="moneda">Moneda:</label>
        <input type="descripcion" name="moneda" id="moneda" required><br><br>

        <label for="sena">sena:</label>
        <input type="text" name="sena" id="sena" required><br><br>

        <label for="com_comprador">Comision Comprador:</label>
        <input type="text" name="com_comprador" id="com_comprador" required><br><br>

        <label for="com_vendedor">Comision Vendedor:</label>
        <input type="text" name="com_vendedor" id="com_vendedor" required><br><br>
         
        <label for="imp_municipal">Impuesto Municipal:</label>
        <input type="text" name="imp_municipal" id="imp_municipal" required><br><br>
        
        <label for="observaciones">Observaciones:</label>
        <input type="text" name="observaciones" id="observaciones" required><br><br>

        <button type="submit" >Guardar cliente</button>
       
    </form>
</body>
</html>