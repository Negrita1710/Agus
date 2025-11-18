<?php
    session_start();
    error_reporting(E_ALL); 
    ini_set('display_errors', 1);
    require_once  '../../percistencia/boletaentrada.php';
    require_once  '../../percistencia/clientes.php';

    $boletaentrada = BoletaEntrada::recuperarTodos();

?>

        
           
        <div class="form-busqueda">
                 <h2>Boleta de entrada</h2>


                <label for="nombre" >Buscar:</label>
                <div class="search-row">
                  <input type="text" class="nav-barra" id="nombre" name="nombre" required>
                  <button type="submit" name="boton" class="nav" id="boton" onclick="buscarboleta()">Listar</button>
                </div>
                <input type="button" id="agregar" class="boton" value="Agregar boleta" onclick="agregarboleta()">
        </div>
           
        <div id="inresultado">

            <table cellspacing=0 class="table">
                <tr>
                    <th>ID</th>
                    <th>Moneda</th>
                    <th>Fecha</th>
                    <th>Clientes</th>
                    <th>Accion</th>

                </tr>
                <?php foreach ($boletaentrada as $boletaEntrada): ?>
                    <tr>
                        <?php
                        $cliente = Clientes::buscarPorId($boletaEntrada['id_cliente']);
                        ?>
                        <td><?php echo htmlspecialchars($boletaEntrada['id']); ?></td>
                        <td><?php echo htmlspecialchars($boletaEntrada['moneda']); ?></td>
                        <td><?php echo htmlspecialchars($boletaEntrada['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($cliente ? $cliente->getNombre() . ' ' . $cliente->getApellido() : ''); ?></td>
                        <td onclick="agregarboleta(<?php echo htmlspecialchars($boletaEntrada['id']); ?>)">
                        <i title="editar" class="fa-solid fa-pencil"></i> </td>
                        
                    </tr>
                <?php endforeach; ?>
   
            </table>
        </div>
            
                    

