<?php
  session_start();

  require_once  '../../percistencia/clientes.php';

  $clientes = Clientes::recuperarTodos();

?>
<DOCTYPE html>
    <html lang="es">
        
        
    <head>
        <link rel ="stylesheet" href="../funcion/css/style.css" type="text/css">
          <script src="https://kit.fontawesome.com/16aa28c921.js"></script>
        <meta charset="UTF-8">
        <title>Clientes</title>
       
    
    </head>
    <body> 
            <div class="form-busqueda">
                 <h2>Lista de Clientes</h2>


                <label for="nombre" >Buscar:</label>
                <div class="search-row">
                  <input type="text" class="nav-barra" id="nombre" name="nombre" required>
                  <button type="submit" name="boton" class="nav" id="boton" onclick="buscarclientes()">Listar</button>
                </div>
                <input type="button" id="agregar" class="boton" value="Agregar Cliente" onclick="agregarclientes()">
            </div>

          <div id="inresultado">



              <table cellspacing=0 class="table">
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
                <?php foreach ($clientes as $cliente): ?>
                  <tr>

                    <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['tipo_documento']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['documento']); ?></td>
                    <td onclick="agregarclientes(<?php echo htmlspecialchars($cliente['id']); ?>)">
                    <i class="fa-solid fa-pencil"></i> </td>
                  </tr>
                <?php endforeach; ?>

              </table>
          </div>
          
                    
            
        </body>

        </html>
