<?php
  session_start();
  error_reporting(E_ALL); 
  ini_set('display_errors', 1);
  require_once  '../../percistencia/remates.php';
  require_once '../../percistencia/remates.php';

  $remates = Remates::recuperarTodos();
  $remates = Remates::recuperarConEstado();

?>

        
    <div class="form-busqueda">    
      <h2>Lista de Remates</h2>
      <label for="nombre" >Buscar:</label>
      <div class="search-row">
        <input type="text" class="nav-barra" id="nombre" name="nombre" required>
        <button type="submit" name="boton" class="nav" id="boton" onclick="buscaremate()">Listar</button>
        <input type="button" id="agregar" class="boton" value="Agregar Remate" onclick="agregaremate()">
        
      </div>
    </div>
           
        
    <div id="inresultado">
      <table class="table">
        <tr>
          <th>Fecha</th>
          <th>Moneda</th>
          <th>sena</th>
          <th>Comision Comprador</th>
          <th>Comision Vendedor</th>
          <th>Impuesto Municipal</th>
          <th>Observaciones</th>
          <th>Estado</th>
          <th>Accion</th>
        </tr>
            <?php foreach ($remates as $remate): 
               if ((int)$remate['total_lotes'] === 0) {
                 $estadoLabel = 'Sin lotes';
              } elseif ((int)$remate['vendidos'] > 0) {
               // si hay ventas, mostrar "Vendido" y remito(s)
             $remitos = $remate['remitos_vendidos'] ? $remate['remitos_vendidos'] : 'N/A';
             $estadoLabel = 'Vendido (remito: ' . htmlspecialchars($remitos) . ')';
             } else {
        $estadoLabel = 'Abierto';
             }
             ?>
              <tr>
                  
                  <td><?php echo htmlspecialchars($remate['fecha']); ?></td>
                  <td><?php echo htmlspecialchars($remate['moneda']); ?></td>
                  <td><?php echo htmlspecialchars($remate['sena']). '%'; ?></td>
                  <td><?php echo htmlspecialchars($remate['com_comprador']). '%'; ?></td>
                  <td><?php echo  htmlspecialchars($remate['com_vendedor']). '%'; ?></td>
                  <td><?php echo  htmlspecialchars($remate['imp_municipal']). '%'; ?></td>
                  <td><?php echo htmlspecialchars($remate['observaciones']); ?></td>
                  <td><?php echo $estadoLabel ?></td>
                   <td >
                    <i title="editar" onclick="agregaremate(<?php echo htmlspecialchars($remate['id']); ?>)" class="fa-solid fa-pencil"></i> <i title="relcionar lotes" class="fa-solid fa-eye" onclick="relacionarlote(<?php echo htmlspecialchars($remate['id']); ?>)"></i></td>
              </tr>
            <?php endforeach; ?>
   
      </table>
    </div>

