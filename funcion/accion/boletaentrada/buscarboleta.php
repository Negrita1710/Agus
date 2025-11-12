<?php

    session_start();

    error_reporting(E_ALL); 
    ini_set('display_errors', 1);


    require_once  '../../percistencia/boletaentrada.php';
    require_once  '../../percistencia/clientes.php';

    if (isset($_GET['buscar'])) {
        
        $busqueda = trim($_GET['buscar']);
        
        $boletas = BoletaEntrada::buscarPor($busqueda);
        $resultados = [];
        foreach ($boletas as $boleta) {
            $resultados[] = $boleta;
        }
    } else {
        $boletas = BoletaEntrada::recuperarTodos();
        $resultados = $boletas;
    }
?>
<div class="h2">
    <h2>Resultados de la busqueda:</h2>
</div>
<div id="inresultado">
    <table class="table">
    <tr>
          <th>ID</th>
          <th>Moneda</th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Accion</th>

    </tr>
            <?php foreach ($resultados as $boleta): ?>
              <tr>
                 <?php
                    $cliente = Clientes::buscarPorId($boleta['id_cliente']);
                 ?>
                <td><?php echo htmlspecialchars($boleta['id']); ?></td>
                <td><?php echo htmlspecialchars($boleta['moneda']); ?></td>
                <td><?php echo htmlspecialchars($boleta['fecha']); ?></td>
                <td><?php echo htmlspecialchars($cliente->getNombre() . " " . $cliente->getApellido()); ?></td>
                <td onclick="agregarboleta(<?php echo htmlspecialchars($boleta['id']); ?>)">
                <i class="fa-solid fa-pencil"></i> </td>
               </tr>
             <?php endforeach; ?>
   
    </table>
</div>