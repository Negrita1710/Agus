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
    //esto es el placeholder gris para las imagenes que no tienen foto
    $placeholder = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2NjYyIvPjwvc3ZnPg==';
?>
<div class="form-agregar">
  <h2>
    <?php if($lotes->getId()){
      $remate_del_lote = null;
      foreach ($remates as $rem) {
          if ($rem['id'] == $lotes->getIdRemate()) { $remate_del_lote = $rem; break; }
      }
      if ($remate_del_lote) {
          echo("Actualizar Lote para " . htmlspecialchars($remate_del_lote['fecha']) . " " . htmlspecialchars($remate_del_lote['moneda']));
      } else {
          if ($remate_actual) {
            echo("Nuevo Lote para " . htmlspecialchars($remate_actual['fecha']) . " " . htmlspecialchars($remate_actual['moneda']));
          } else {
            echo("Nuevo Lote");
          }
      }
    } ?>
  </h2>

  <!-- FORM: id importante para que el JS lo capture -->
  <form id="form-lote" action="../funcion/accion/lotes/actualizarlote.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_lote" id="idlote" value="<?php echo htmlspecialchars($lotes->getId()); ?>">
    <input type="hidden" name="id_remate" value="<?php echo htmlspecialchars($id_remate); ?>">

      <div class="objetos-grid" id="objetos-grid">
        <?php foreach ($objetos as $obj): ?>
          <?php
            $foto = htmlspecialchars($obj['foto']);
            if (!empty($foto)) {
              // Remove 'uploads/' prefix if present to avoid double path
              $foto = str_replace('uploads/', '', $foto);
              $src = '/funcion/accion/boletaentrada/uploads/' . $foto;
            } else {
              $src = $placeholder;
            }
          ?>
          <!-- Debug: <?php echo 'Foto: ' . htmlspecialchars($obj['foto']) . ' | Src: ' . $src; ?> -->
          <label class="obj-card">
            <input type="checkbox" name="id_objeto[]" value="<?php echo $obj['id']; ?>">
          
            <div class="thumb">
              <img src="<?php echo $src; ?>" alt="<?php echo htmlspecialchars($obj['nombre']); ?>" width="100" height="100">
            </div>
            <div class="meta">
              <div class="nombre"><?php echo htmlspecialchars($obj['nombre']); ?></div>
              <div class="moneda"><?php echo htmlspecialchars($obj['moneda']); ?></div>
                <input type="file" name="foto[]"  multiple onchange="previewImages(event)">
                
            </div>


          </label>
        <?php endforeach; ?>
      </div>
     
               
      <div id="preview"></div>


    <div class="lote">
    <p>Numero:</p>
    <input type="text" name="numero" id="numero" required value="<?php echo htmlspecialchars($lotes->getNumero()); ?>">
    <p>Serie:</p>
    <input type="text" name="serie" id="serie" required value="<?php echo htmlspecialchars($lotes->getSerie()); ?>">

     </div>

    <div id="mensaje-remate" style="color:green;margin-top:8px;"></div>
    <!-- submit normal: tu JS debe interceptar el submit del form-lote -->
    <button type="submit" class="boton-guardar">Guardar cambios</button>
    </form>
 
</div>

 <!-- mas adelante tengo que ver si los clientes pueden ver las imagenes que se priorizaron  -->
