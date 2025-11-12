<?php
    session_start(); 
    require_once '../../percistencia/remates.php';
    $id = $_GET['id'] ?? null;
    $remates = Remates::buscarPorId($id);
    if (!$remates) {
        $remates = new Remates ('', '', '', '', '', '', '');
    }
?>


    <h2 class="h2">
    <?php if($remates->getId()){
        echo("Actualizar Remate");
      }else{
        echo("Nuevo Remate");
      } ?>
    </h2>
    <div class="form-remate">
        <div id="inresultado">
        <form id="form-remate" method="post" action="../funcion/accion/remates/actualizaremate.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($remates->getId()); ?>">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" required value="<?php echo htmlspecialchars($remates->getFecha()); ?>"><br><br>
             <label for="moneda">Moneda:</label>
            <select name="moneda" id="moneda" required>
              <option>Seleccione una moneda</option>
              <option value="USD" <?php echo ($remates->getMoneda() == 'USD') ? 'selected' : ''; ?>>USD</option>
              <option value="Pesos" <?php echo ($remates->getMoneda() == 'Pesos') ? 'selected' : ''; ?>>Pesos</option>
            </select>
            <label for="sena">sena:</label>
            <input type="text" name="sena" id="sena" required value="<?php echo htmlspecialchars($remates->getsena()); ?>"><br><br>
            
            <label for="observaciones">Observaciones:</label>
            <input type="text" name="observaciones" id="observaciones" required value="<?php echo htmlspecialchars($remates->getObservaciones()); ?>"><br><br>

            <label for="com_comprador">Comision Comprador:</label>
            <input type="text" name="com_comprador" id="com_comprador" required value="<?php echo htmlspecialchars($remates->getComComprador()); ?>"><br><br>

            <label for="com_vendedor">Comision Vendedor:</label>
            <input type="text" name="com_vendedor" id="com_vendedor" required value="<?php echo htmlspecialchars($remates->getComVendedor()); ?>"><br><br>
            
            <label for="imp_municipal">Impuesto Municipal:</label>
            <input type="text" name="imp_municipal" id="imp_municipal" required value="<?php echo htmlspecialchars($remates->getImpMunicipal()); ?>"><br><br>
            

        
            <div id="mensaje-remate" style="color:green;margin-top:8px;"></div>

            <div class="acciones" style="text-align:right;margin-top:12px;">
                <button type="submit" class="boton">Guardar</button>
            </div>

        </form>
        </div>
    </div>
