<?php
require_once ' ../../percistencia/objetos.php';
?>

         
            
            <body>
            <div class="form-agregar">
        <div id="inresultado">
            <h2>Editar producto</h2>
                <form method="post" action="../funcion/accion/clientes/editarproductos.php" class="form-cliente">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto->getId()); ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($producto->getNombre()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="text" name="cantidad" id="cantidad" required value="<?php echo htmlspecialchars($producto->getCantidad()); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="direccion">Descripción:</label>
                        <input type="text" name="direccion" id="direccion" required value="<?php echo htmlspecialchars($producto->getDescripcion()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="valor_esperado">Valor esperado:</label>
                        <input type="text" name="telefono" id="valor_esperado" required value="<?php echo htmlspecialchars($producto->getValorEsperado()); ?>">
                    </div>
                </div>

                

                 <button type="submit" class="boton-guardar">Guardar cambios</button>
                </form>
        </div>    
        </body>
