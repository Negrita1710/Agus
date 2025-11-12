<?php
    session_start(); 
    require_once '../../percistencia/clientes.php';
    $id = $_GET['id'] ?? null;
    $cliente = Clientes::buscarPorId($id);
    if (!$cliente) {
        $cliente = new Clientes('', '', '', '', '', '');
    }  
?>


    <head>
        <title>Formulario cliente</title>
    </head>
        <body>
        <div class="form-agregar">
        <div id="inresultado">
            <h2>Formulario de Cliente</h2>
                <form method="post" action="../funcion/accion/clientes/actualizarclientes.php" class="form-cliente">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($cliente->getId()); ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($cliente->getNombre()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" name="apellido" id="apellido" required value="<?php echo htmlspecialchars($cliente->getApellido()); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" required value="<?php echo htmlspecialchars($cliente->getDireccion()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono" id="telefono" required value="<?php echo htmlspecialchars($cliente->getTelefono()); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="documento">Documento:</label>
                        <input type="text" name="documento" id="documento" required value="<?php echo htmlspecialchars($cliente->getDocumento()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="tipo_documento">Tipo de Documento:</label>
                        <input type="text" name="tipo_documento" id="tipo_documento" required value="<?php echo htmlspecialchars($cliente->getTipoDocumento()); ?>">
                    </div>
                </div>

                 <button type="submit" class="boton-guardar">Guardar cliente</button>
                </form>
        </div>
        </div>
</body>
