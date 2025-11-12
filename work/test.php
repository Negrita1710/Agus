<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Boleta Test</title>
</head>
<body>

<form>
  <table>
    <tbody id="productos-body">
      <tr>
        <td><input type="text" name="productos[0][nombre]"></td>
        <td><input type="number" name="productos[0][cantidad]"></td>
        <td><input type="text" name="productos[0][descripcion]"></td>
        <td><input type="number" name="productos[0][valor_esperado]"></td>
      </tr>
    </tbody>
  </table>
  <button type="button" id="btn-agregar-producto">Agregar otro producto</button>
</form>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    let productoIndex = 1;

    const btnAgregar = document.getElementById('btn-agregar-producto');
    if (btnAgregar) {
      btnAgregar.addEventListener('click', function (event) {
        alert("Click detected"); // ✅ Should trigger
        event.preventDefault();

        const tbody = document.getElementById('productos-body');
        if (!tbody) {
          alert("Tbody not found");
          return;
        }

        const fila = document.createElement('tr');
        fila.innerHTML = `
          <td><input type="text" name="productos[${productoIndex}][nombre]"></td>
          <td><input type="number" name="productos[${productoIndex}][cantidad]"></td>
          <td><input type="text" name="productos[${productoIndex}][descripcion]"></td>
          <td><input type="number" name="productos[${productoIndex}][valor_esperado]"></td>
        `;
        tbody.appendChild(fila);
        productoIndex++;
      });
    }
  });
</script>
</body>
</html>
