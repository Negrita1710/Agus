<?php
    session_start();
    require_once '../../percistencia/lote.php';
    require_once '../../percistencia/remates.php';
    require_once '../../percistencia/objetos.php';
    $id = $_GET['id'] ?? null;
    $id_remate = $_GET['id_remate'] ?? null;
    $lotes = Lote::buscarPorId($id);
    if (!$lotes) {
        $lotes = new Lote ($id_remate, '', '', '');
    }
    $remates = Remates::recuperarTodos();
      $objetos = Objetos::recuperarTodosConMoneda();
    $remate_actual = null;
    if ($id_remate) {
        foreach ($remates as $rem) {
            if ($rem['id'] == $id_remate) {
                $remate_actual = $rem;
                break;
            }
        }
    }  
    $disponibles = Lote::recuperarDisponibles();        
?>


  <div class="form-agregar">
      <h2>
       <?php if($lotes->getId()){
         // Para actualizar, obtener el remate del lote
         $remate_del_lote = null;
         foreach ($remates as $rem) {
             if ($rem['id'] == $lotes->getIdRemate()) {
                 $remate_del_lote = $rem;
                 break;
             }
         }
         if ($remate_del_lote) {
             echo("Actualizar Lote para " . htmlspecialchars($remate_del_lote['fecha']) . " " . htmlspecialchars($remate_del_lote['moneda']));
       }else{
        if ($remate_actual) {
       echo("Nuevo Lote para " . htmlspecialchars($remate_actual['fecha']) . " " . htmlspecialchars($remate_actual['moneda']));
       } else {
           echo("Nuevo Lote");
        }
       }
       } ?>
     </h2>
     
       <input type="hidden" name="id" id="idlote" value="<?php echo htmlspecialchars($lotes->getId()); ?>">

      <!-- Tabla de información -->
           
                    <div class="objetos-grid" id="objetos-grid">
              <?php foreach ($objetos as $obj): 
                $checked = in_array($obj['id'], (array)$lotes->getIdObjeto()) ? 'checked' : '';
                
              ?>
              
              
                <label class="obj-card">
                  <input type="checkbox" name="id_objeto[]" value="<?php echo $obj['id']; ?>" <?php echo $checked; ?>>
                  <div class="thumb"><img src="uploads/<?php echo $obj['id']; ?>.jpg" alt="Imagen del producto" alt="<?php echo htmlspecialchars($obj['nombre']); ?>"></div>
                  <div class="meta">
                    <div class="nombre"><?php echo htmlspecialchars($obj['nombre']); ?></div>
                    <div class="moneda"><?php echo htmlspecialchars($obj['moneda']); ?></div>
                  </div>
                </label>
                
              <?php endforeach; ?>
        
               </div>
            

            
 
            <p>Numero:</p>  
            <input type="text" name="numero" id="numero" required value="<?php echo htmlspecialchars($lotes->getNumero()); ?>">
            <p>Serie:</p>
            <input type="text" name="serie" id="serie" required value="<?php echo htmlspecialchars($lotes->getSerie()); ?>">
   
    

      <div id="mensaje-remate" style="color:green;margin-top:8px;"></div>

      <button type="button" class="boton-guardar" onclick="ActualizarLote();">Guardar cambios</button>
    </div>
              