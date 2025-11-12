<?php

    session_start();

    error_reporting(E_ALL); 
    ini_set('display_errors', 1);


    require_once  '../../percistencia/clientes.php';

    if (isset($_GET['buscar'])) {
        
        $busqueda = trim($_GET['buscar']);
        
        $clientes = Clientes::buscarPor($busqueda);
        $resultados = [];
        foreach ($clientes as $cliente) {
            $resultados[] = $cliente;
        }
    } else {
        $clientes = Clientes::recuperarTodos();
        $resultados = $clientes;
    }
?>
<div class="h2">
    <h2>Resultados de la busqueda:</h2>
</div>
<div id="inresultado">
    <table class="table">
    
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Tipo de Documento</th>
                <th>Documento</th>
                <th>Accion</th>
            </tr>
                 <?php foreach ($resultados as $cliente): ?>
                    <tr>  
                        <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['tipo_documento']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['documento']); ?></td>
                         <td onclick="agregarboleta(<?php echo htmlspecialchars($cliente['id']); ?>)">
                <i class="fa-solid fa-pencil"></i> </td>
                    </tr>
                <?php endforeach; ?>
   
   
    </table>
</div>




