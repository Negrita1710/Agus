<?php

    session_start();

    error_reporting(E_ALL); 
    ini_set('display_errors', 1);


    require_once  '../../percistencia/remates.php';

    if (isset($_GET['buscar'])) {
        
        $busqueda = trim($_GET['buscar']);
        
        $remates = Remates::buscarPor($busqueda);
        $resultados = [];
        foreach ($remates as $remate) {
            $resultados[] = $remate;
        }
    } else {
        $remates = Remates::recuperarTodos();
        $resultados = $remates;
    }
?>
    <div class="h2">
        <h2>Resultados de la busqueda:</h2>
    </div>
<div id="inresultado">
    <table class="table">
       
       <tr>
          <th>ID</th>
          <th>Fecha</th>
          <th>Moneda</th>
          <th>sena</th>
          <th>Comision Comprador</th>
          <th>Comision Vendedor</th>
          <th>Impuesto Municipal</th>
          <th>Observaciones</th>
          <th>Accion</th>
        </tr>
             <?php foreach ($resultados as $remate): ?>
                <tr>
                
                    <td><?php echo htmlspecialchars($remate['id']); ?></td>
                    <td><?php echo htmlspecialchars($remate['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($remate['moneda']); ?></td>
                    <td><?php echo htmlspecialchars($remate['sena']); ?></td>
                    <td><?php echo htmlspecialchars($remate['com_comprador']); ?></td>
                    <td><?php echo htmlspecialchars($remate['com_vendedor']); ?></td>
                    <td><?php echo htmlspecialchars($remate['imp_municipal']); ?></td>
                    <td><?php echo htmlspecialchars($remate['observaciones']); ?></td>
                </tr>
            <?php endforeach; ?>
            
   
    </table>
</div>