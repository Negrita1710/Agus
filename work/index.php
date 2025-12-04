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

            // Attach event listener for product form
            const form = document.getElementById('uploadform');
            if (form) {
                form.addEventListener('submit', function(e){
                    e.preventDefault();

                    const formData = new FormData(form);

                    // Get boleta data if id_boleta is 0
                    const idBoletaInput = document.querySelector('input[name="id_boleta"]');
                    const idBoleta = idBoletaInput ? idBoletaInput.value : 0;
                    formData.append('id_boleta', idBoleta);

                    if (idBoleta == 0) {
                        // Add boleta data
                        const moneda = document.getElementById('moneda').value;
                        const fecha = document.getElementById('fecha').value;
                        const id_cliente = document.getElementById('id_cliente').value;
                        formData.append('moneda', moneda);
                        formData.append('fecha', fecha);
                        formData.append('id_cliente', id_cliente);
                    }

                    fetch('../funcion/accion/boletaentrada/procesar_productos.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(resp => resp.json())
                    .then(json => {
                        if (json.ok) {
                            alert('Producto guardado exitosamente');
                            location.reload();
                        } else {
                            alert('Error: ' + (json.error || 'Error desconocido'));
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Error al guardar el producto');
                    });
                });
            }

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
                
            }
        };
        xhr.open('GET', '../funcion/accion/lotes/formlote.php?id_remate=' + id_remate, true);
        xhr.send();
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
   
            // delegación: evitar submit si no hay objetos seleccionados
            //estO sirve para mejorar la seleccion / validacion
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadform');
    if (form) {
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const formData = new FormData(form);

            if (!formData.has('id_boleta')) {
                const idBoleta = document.querySelector('input[name="id_boleta"]')?.value || 0;
                formData.append('id_boleta', idBoleta);
            }

            fetch('../funcion/accion/boletaentrada/procesar_productos.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.json())
            .then(json => {
                if (json.ok) {
                    alert('Producto guardado exitosamente');
                    location.reload();
                } else {
                    alert('Error: ' + (json.error || 'Error desconocido'));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error al guardar el producto');
            });
        });
    }
});

function previewImages(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    const files = event.target.files;

    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML += `
              <div style="margin:10px; display:inline-block;">
                <img src="${e.target.result}" width="100"><br>
                <label>
                  <input type="radio" name="prioridad" value="${i}">
                  Marcar como prioritaria
                </label>
              </div>
            `;
        }
        reader.readAsDataURL(files[i]);
    }
}
</script>