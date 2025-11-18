<?php
session_start();
?>

<DOCTYPE html>
    <html lang="es">
       
        
    <head>
        <script src="https://kit.fontawesome.com/16aa28c921.js"></script>
        <meta charset="UTF-8">
        <title>Remates</title>
        
        <link rel ="stylesheet" href="../funcion/css/style.css" type="text/css">
       
    </head>
    <body>
       
        <div class=header>
            <h2>Remates</h2>
             
            <i class="fa-solid fa-user-tie"></i>   
             
            <?php
                
                echo $_SESSION['usuario_nombre'];
            ?> <br>
        
            <input type="button" id="logout" value="Cerrar Sesion" onclick="location.href='../cierresesion.php'">
            
        </div>
            <div class=row>
                <input type="button" class="buttonClientes" id="cli" value="Clientes"><br><br>
                <input type="button" id="entrada" class="buttonClientes" value="Boleta de entrada"><br><br>
                <input type="button" id="rem" class="buttonClientes" value="Remates"><br><br>
            </div>

            <div class="menu li" id="resultado">
                <div id="inresultado">
                </div>
                    
            </div>
            
        
            <div class=footer>
                <p>Pie de página</p>
            </div>
           
     

        
    </body>
</html>
<script>
    document.getElementById('cli').addEventListener('click', function() {
        
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('resultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/clientes/listarclientes.php', true);
        xhr.send();
    });



    function buscarclientes() {
        
        var nombre = document.getElementById('nombre').value;
    
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('inresultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/clientes/buscarclientes.php?buscar=' + nombre, true);
        xhr.send();
    }
//Pide el id para distinguir dos modos:
//Si recibes un id -> cargar el formulario en modo "editar" (el servidor carga los datos del cliente con ese id y rellena los campos).
//Si no pasas id (o es vacío) -> cargar el formulario en modo "nuevo" (campos vacíos para crear un cliente).
    function  agregarclientes(id) {
    
        

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('inresultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/clientes/fomclientes.php?id=' + id, true);
        xhr.send();
    };


    document.getElementById('entrada').addEventListener('click', function() {
    
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('resultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/boletaentrada/listarboleta.php?id=');
        xhr.send();
    });


    function  buscarboleta() {
        var nombre = document.getElementById('nombre').value;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(document.getElementById('inresultado'));
                document.getElementById('inresultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/boletaentrada/buscarboleta.php?buscar='+ nombre, true);
        xhr.send();
    };
    //
    function agregarboleta(id) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('inresultado').innerHTML = this.responseText;
            

        }
    };
     xhr.open('GET', '../funcion/accion/boletaentrada/formboleta.php?id=' + id, true);
        xhr.send();
}
    document.getElementById('rem').addEventListener('click', function() {
        
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('resultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/remates/listaremate.php', true);
        xhr.send();
    });
    function setupFormRemate() {
    const form = document.getElementById('form-remate');
    const msg = document.getElementById('mensaje-remate');
    if (!form) return; // nada que hacer

    // evitar múltiples bindings
    if (form._bound) return;
    form._bound = true;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        msg && (msg.textContent = 'Guardando...');

        // usar FormData para compatibilidad con PHP ($_POST)
        const data = new FormData(form);

        fetch(form.action || '../funcion/accion/remates/actualizaremate.php', {
            method: 'POST',
            body: data,
            cache: 'no-store'
        })
        .then(resp => resp.json())
        .then(json => {
            if (json.ok) {
                window.location.href = '../index.php';
            } else {
                msg && (msg.textContent = 'Error: ' + (json.error || 'respuesta inesperada'));
            }
        })
        .catch(err => {
            msg && (msg.textContent = 'Error al guardar.');
            console.error(err);
        });
    });
}
    function  agregaremate(id) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('inresultado').innerHTML = this.responseText;
                setupFormRemate();
            }
        };
        xhr.open('GET', '../funcion/accion/remates/formremate.php?id=' + id, true);
        xhr.send();
    };
      function  buscaremate() {
        var nombre = document.getElementById('nombre').value;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(document.getElementById('inresultado'));
                document.getElementById('inresultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/boletaentrada/buscarboleta.php?buscar='+ nombre, true);
        xhr.send();
    };
 function relacionarlote(id_remate){
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('inresultado').innerHTML = this.responseText;
                setupFormLote();
            }
        };
        xhr.open('GET', '../funcion/accion/lotes/formlote.php?id_remate=' + id_remate, true);
        xhr.send();
}

function setupFormLote() {
    document.getElementById('id_remate').addEventListener('change', function() {
        var remateMoneda = this.options[this.selectedIndex].text.split(' ')[1]; // Assuming format "fecha moneda"
        var objetoSelect = document.getElementById('id_objeto');
        var options = objetoSelect.options;
        for (var i = 0; i < options.length; i++) {
            var option = options[i];
            if (option.value === '') continue; // Skip empty option
            var objetoMoneda = option.text.split('(')[1].split(')')[0]; // Extract moneda from "(moneda)"
            if (objetoMoneda.toLowerCase() === remateMoneda.toLowerCase()) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }
        // Reset selection if current is hidden
        var selectedOptions = [];
        for (var i = 0; i < options.length; i++) {
            if (options[i].selected && options[i].style.display === 'none') {
                options[i].selected = false;
            } else if (options[i].selected) {
                selectedOptions.push(options[i]);
            }
        }
        // If no options selected, select the first visible one
        if (selectedOptions.length === 0) {
            for (var i = 0; i < options.length; i++) {
                if (options[i].value !== '' && options[i].style.display !== 'none') {
                    options[i].selected = true;
                    break;
                }
            }
        }
    });
}

function ActualizarLote() {
    const form = document.getElementById('form-lote');
    const msg = document.getElementById('mensaje-lote');
    if (!form) return;

    // Evitar doble binding si lo llamás varias veces
    if (form._bound_lote) return;
    form._bound_lote = true;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        msg && (msg.textContent = 'Guardando...');
        const data = new FormData(form); // envia id_objeto[] correctamente

        fetch(form.action || '../funcion/accion/lotes/actualizarlote.php', {
            method: 'POST',
            body: data,
            cache: 'no-store'
        })
        .then(r => r.json())
        .then(json => {
            if (json.ok) {
                msg && (msg.textContent = json.message || 'Guardado correctamente.');
                document.getElementById('lote')?.click(); // refrescar lista
            } else {
                msg && (msg.textContent = 'Error: ' + (json.error || 'respuesta inesperada'));
            }
        })
        .catch(err => {
            msg && (msg.textContent = 'Error al guardar.');
            console.error(err);
        });
    });
}

function listarlote() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('inresultado').innerHTML = this.responseText;
        }
    };
    xhr.open('GET', '../funcion/accion/lotes/listarlote.php', true);
    xhr.send();
}
    function listaremate() {        
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('resultado').innerHTML = this.responseText;
            }
        };
        xhr.open('GET', '../funcion/accion/remates/listaremate.php', true);
        xhr.send();
    };
    // ...existing code...
// delegación: evitar submit si no hay objetos seleccionados (si lo requerís)
//estp sirve para mejorar la seleccion / validacion
document.addEventListener('submit', function (e) {
  if (!e.target || e.target.id !== 'form-lote') return;
  // comprobá que haya al menos una casilla marcada
  const checked = e.target.querySelectorAll('input[name="id_objeto[]"]:checked').length;
  if (checked === 0) {
    e.preventDefault();
    const msg = document.getElementById('mensaje-remate') || document.getElementById('mensaje-lote');
    if (msg) msg.textContent = 'Seleccioná al menos un objeto para el lote.';
    return;
  }
  // el resto del handler ya enviará FormData como lo tengas implementado
});
    function borrar_objeto(id) {
        fetch('../funcion/accion/boletaentrada/borrar_objeto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                alert('Objeto borrado con éxito');
            }else {
                alert('No se pudo borrar el objeto');
            }
            console.log('Objeto borrado:', data);
            // Eliminar la fila de la tabla correspondiente al objeto borrado
            const fila = document.getElementById('borrar_producto_' + id).closest('tr');
            if (fila) {
                fila.remove();
            }
        })
        .catch(error => {
            console.error('Error al borrar el objeto:', error);
        });
   
        
   
    };



  function guardarCambios() {
    const form = document.getElementById('form-productos');
    if (!form) {
        console.error("No se encontró el form");
        return;
    }

    const data = new FormData(form);

    // Agregar campos extra
    data.append('moneda', document.getElementById('moneda').value);
    data.append('fecha', document.getElementById('fecha').value);
    data.append('id_cliente', document.getElementById('id_cliente').value);
    data.append('id', document.getElementById('idboleta').value);

    alert('Guardando cambios...');

    fetch('../funcion/accion/boletaentrada/actualizarboletadeentrada.php', {
        method: 'POST',
        body: data,
        cache: 'no-store'
    })
    .then(resp => resp.json())
    .then(json => {
        if (json.ok) {
            alert('Guardado exitosamente');
            location.reload();
        } else {
            alert('Error: ' + (json.error || 'respuesta inesperada'));
        }
    })
    .catch(err => {
        console.error('Error en la solicitud:', err);
        alert('Error al guardar la boleta');
    });
}
function guardarProducto(){
  const form = document.getElementById('form-productos');
  const productosBody = document.getElementById('productos-body');
  console.log("form:", form);
  console.log("productosBody:", productosBody);
  if (!form || !productosBody) {
  console.error("No se encontró el form o el tbody");
   return;
  }
    const data = new FormData(form);
    alert('Guardando cambios...');

    fetch('../funcion/accion/boletaentrada/editarproductos.php', {
    method: 'POST',
    body: data,
    cache: 'no-store'
  })
  }

</script>