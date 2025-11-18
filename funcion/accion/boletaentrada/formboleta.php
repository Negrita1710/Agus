<?php
session_start(); 
require_once '../../percistencia/boletaentrada.php';
require_once '../../percistencia/clientes.php';
require_once '../../percistencia/objetos.php';

$id = $_GET['id'] ?? null;
$boletaentrada = BoletaEntrada::buscarPorId($id);
$clientes = Clientes::recuperarTodos();
if (!$boletaentrada) {
    $boletaentrada   = new BoletaEntrada('', '', '', '');
}   

$productos = [];
if ($boletaentrada && $boletaentrada->getId()) {
    $productos = BoletaEntrada::obtenerProductosPorBoleta($boletaentrada->getId());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Boleta de entrada</title>
  <link rel="stylesheet" href="../../css/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="form-agregar">
    <h2>
      <?php if($boletaentrada->getId()){
        echo("Actualizar Boleta");
      }else{
        echo("Nueva Boleta");
      } ?>
    </h2>
    <div id="formboleta">
      <input type="hidden" name="id" id="idboleta" value="<?php echo ($boletaentrada->getId()); ?>">

      <!-- Tabla de información -->
       <form id="tabla-infoboletaE" method="post">
      <table class="tabla-productos">
        <tr>
          <th>Moneda</th>
          <th>Fecha</th>
          <th>Id Cliente</th>
          <th>Acciones</th>
        </tr>
        <tr>
  <td>
          <select name="moneda" id="moneda" required>
            <option>Seleccione una moneda</option>
            <option value="USD" <?php echo ($boletaentrada->getMoneda() == 'USD') ? 'selected' : ''; ?>>USD</option>
            <option value="Pesos" <?php echo ($boletaentrada->getMoneda() == 'Pesos') ? 'selected' : ''; ?>>Pesos</option>
          </select>
        </td>
        <td>
          <input type="date" name="fecha" id="fecha" required value="<?php echo($boletaentrada->getFecha()); ?>">
        </td>
        <td>
          <select name="id_cliente" id="id_cliente" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cli): ?>
              <option value="<?php echo $cli['id']; ?>" 
                <?php echo ($cli['id'] == $boletaentrada->getIdCliente()) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cli['nombre'] . ' ' . $cli['apellido']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </td>
        <td>
          <i title="Guardar cambios"onclick="guardarCambios()" class="fa-solid fa-floppy-disk"></i>
        </td>
      </tr>

      </table>
      </form>

      <!-- Tabla de productos -->
      <h2>Productos</h2>
      <form id="form-productos" class="form-productos" method="POST" enctype="multipart/form-data">
  <table class="tabla-productos">
    <thead>
      <tr>
        <th>Imagenes</th>
        <th>Nombre</th>
        <th>Cantidad</th>
        <th>Descripción</th>
        <th>Valor esperado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="productos-body">
         <?php if (empty($productos)): ?>
        <tr>
          <td><input type="file" name="imagenes" accept="image/*"></td>
          <td>
            <input type="text" name="nombre" required>
            <input type="hidden" name="id" value="">
          </td>
          <td><input type="number" name="cantidad" required></td>
          <td><input type="text" name="descripcion"></td>
          <td><input type="number" step="0.01" name="valor"></td>
         
          <td>
             <i title="Guardar cambios"onclick="guardarProducto()" class="fa-solid fa-floppy-disk"></i>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($productos as $index => $prod): ?>
          <tr>
            <td><input type="file" name="productos[<?php echo $index; ?>][foto]" accept="image/*"></td>
            <td>
              <input type="text" name="objetos[<?php echo $index; ?>][nombre]" value="<?php echo htmlspecialchars($prod['nombre']); ?>" required>
              <input type="hidden" name="objetos[<?php echo $index; ?>][id]" value="<?php echo $prod['id']; ?>">
            </td>
            <td><input type="number" name="objetos[<?php echo $index; ?>][cantidad]" value="<?php echo htmlspecialchars($prod['cantidad']); ?>" required></td>
            <td><input type="text" name="objetos[<?php echo $index; ?>][descripcion]" value="<?php echo htmlspecialchars($prod['descripcion']); ?>"></td>
            <td><input type="number" step="0.01" name="objetos[<?php echo $index; ?>][valor_esperado]" value="<?php echo htmlspecialchars($prod['valor_esperado']); ?>"></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
            
    </tbody>
  </table>
</form>



<!-- cambie la logica, lo que preciso hacer ahora es hacer algo parecido a lo q habia antes, sobre que si no hay productos la tabla no tenga nd, etc. -->
   <table class="tabla-productos">
                    <tr>
                      <th>Nombre</th>
                      <th>Cantidad</th>
                      <th>Descripcion</th>
                      <th>Valor esperado</th>

                    </tr>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($producto['valor_esperado']); ?></td>
                        <td onclick="(<?php echo htmlspecialchars($producto['id']); ?>)">
                        <i title="editar" class="fa-solid fa-pencil"></i> </td>
                        
                    </tr>
                <?php endforeach; ?>
      </table>
   
    </div>
  </div>

</html>
