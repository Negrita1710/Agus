<?php
session_start(); 
require_once '../../percistencia/boletaentrada.php';
require_once '../../percistencia/clientes.php';

$id = $_GET['id'] ?? null;
$boletas = BoletaEntrada::buscarPorId($id);
$clientes = Clientes::recuperarTodos();
if (!$boletas) {
    $boletas = new BoletaEntrada('', '', '', '');
}   

$productos = [];
if ($boletas && $boletas->getId()) {
    $productos = BoletaEntrada::obtenerProductosPorBoleta($boletas->getId());
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
      <?php if($boletas->getId()){
        echo("Actualizar Boleta");
      }else{
        echo("Nueva Boleta");
      } ?>
    </h2>
    <div id="formboleta">
      <input type="hidden" name="id" id="idboleta" value="<?php echo ($boletas->getId()); ?>">

      <!-- Tabla de información -->
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
              <option value="USD" <?php echo ($boletas->getMoneda() == 'USD') ? 'selected' : ''; ?>>USD</option>
              <option value="Pesos" <?php echo ($boletas->getMoneda() == 'Pesos') ? 'selected' : ''; ?>>Pesos</option>
            </select>
          </td>
          <td>
            <input type="date" name="fecha" id="fecha" required value="<?php echo($boletas->getFecha()); ?>">
          </td>
          <td>
            <select name="id_cliente" id="id_cliente" required>
              <option value="">Seleccione un cliente</option>
              <?php foreach ($clientes as $cli): ?>
                <option value="<?php echo $cli['id']; ?>" <?php echo ($cli['id'] == $boletas->getIdCliente()) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($cli['nombre'] . ' ' . $cli['apellido']); ?>
                </option>
                <td>
                     <input type="button" class="boton-guardar" onclick="agregarFilaProducto()">Guardar cambios</input>
                </td>

              <?php endforeach; ?>
            </select>
          </td>
        </tr>
      </table>

      <!-- Tabla de productos -->
      <h2>Productos</h2>
      <form id="form-productos" class="form-productos" method="post" enctype="multipart/form-data">
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
          <td><input type="file" name="productos[0][foto]" accept="image/*"></td>
          <td>
            <input type="text" name="objetos[0][nombre]" required>
            <input type="hidden" name="objetos[0][id]" value="">
          </td>
          <td><input type="number" name="objetos[0][cantidad]" required></td>
          <td><input type="text" name="objetos[0][descripcion]"></td>
          <td><input type="number" step="0.01" name="objetos[0][valor_esperado]"></td>
          <td></td>
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
                    <th>Accion</th>

                </tr>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <?php
                        $cliente = Clientes::buscarPorId($producto['id_cliente']);
                        ?>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($descripcion['valor_esperado']); ?></td>
                        <td onclick="(<?php echo htmlspecialchars($producto['id']); ?>)">
                        <i title="editar" class="fa-solid fa-pencil"></i> </td>
                        
                    </tr>
                <?php endforeach; ?>
      </table>
   
    </div>
  </div>


  <script>
    let filaIndex = <?php echo count($productos); ?>;

    function agregarFilaProducto() {
      filaIndex++;
      const tbody = document.getElementById('productos-body');
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="file" name="productos[${filaIndex}][foto]" accept="image/*"></td>
        <td>
          <input type="text" name="objetos[${filaIndex}][nombre]" required>
          <input type="hidden" name="objetos[${filaIndex}][id]" value="">
        </td>
        <td><input type="number" name="objetos[${filaIndex}][cantidad]" required></td>
        <td><input type="text" name="objetos[${filaIndex}][descripcion]"></td>
        <td><input type="number" step="0.01" name="objetos[${filaIndex}][valor_esperado]"></td>
        <td><button type="button" onclick="eliminarFila(this)">Eliminar</button></td>
      `;
      tbody.appendChild(tr);
    }

    function eliminarFila(button) {
      const tr = button.closest('tr');
      tr.remove();
    }

    function guardarCambios() {
      var formData = new FormData(document.getElementById('form-productos'));
      formData.append('moneda', $('#moneda').val());
      formData.append('fecha', $('#fecha').val());
      formData.append('id_cliente', $('#id_cliente').val());
      formData.append('id', $('#idboleta').val());

      $.ajax({
        url: 'actualizarboleta.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          try {
            var data = JSON.parse(response);
            if (data.ok) {
              alert('Guardado exitosamente');
              location.reload();
            } else {
              alert('Error: ' + data.error);
            }
          } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response:', response);
            alert('Error en la respuesta del servidor');
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', status, error);
          alert('Error en la solicitud');
        }
      });
    }
  </script>
</html>
