<?php
  session_start();
  error_reporting(E_ALL); 
  ini_set('display_errors', 1);
  require_once  '../../percistencia/lote.php';

  $lotes = Lote::recuperarTodos();

?>
<!DOCTYPE html>
    <html lang="es">
        
        
    <head>
        <link rel ="stylesheet" href="../funcion/css/style.css" type="text/css">
          <script src="https://kit.fontawesome.com/16aa28c921.js"></script>
        <meta charset="UTF-8">
        <title>lotes</title>
    </head>
  <body>
        
    <div class="form-busqueda">    
      <h2>Lista de Lote</h2>
      <label for="nombre" >Buscar:</label>
      <div class="search-row">
        <i title="listar remate"class="fa-solid fa-arrow-rotate-left" onclick="listaremate()"></i>
        
      </div>
    </div>
           

    <div id="inresultado">
      <table class="table">
        <tr>
          <th>numero</th>
          <th>Serie</th>
          <th>Accion</th>
        </tr>
            <?php foreach ($lotes as $lote): ?>
              <tr>

                  <td><?php echo htmlspecialchars($lote['numero']); ?></td>
                  <td><?php echo htmlspecialchars($lote['serie']); ?></td>
                   <td onclick="agregarlote(<?php echo htmlspecialchars($lote['id']); ?>)">
                    <i title="editar" class="fa-solid fa-pencil"></i> </td>
              </tr>
            <?php endforeach; ?>
      </table>
    </div>
  </body>
</html>



