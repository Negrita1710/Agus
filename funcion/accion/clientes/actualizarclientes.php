<?php
    //require_once sirve para incluir y evaluar el archivo especificado durante la ejecución del script. 
    // Si el archivo no se encuentra, genera un error fatal y detiene la ejecución del script.
    require_once '../../percistencia/clientes.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $documento = $_POST['documento'] ?? '';
        $tipo_documento = $_POST['tipo_documento'] ?? '';
        $email = $_POST['email'] ?? '';

        if ($id > 0) {
            // Actualizar usuario existente
            $clientes = Clientes::buscarPorId($id);
            if ($clientes) {
                $clientes->setId($id);
                $clientes->setNombre($nombre);
                $clientes->setApellido($apellido);
                $clientes->setTelefono($telefono);
                $clientes->setDireccion($direccion);
                $clientes->setDocumento($documento);
                $clientes->setTipoDocumento($tipo_documento);
                $clientes->guardar();
                
                echo "Cliente actualizado correctamente.";
                echo  '<a href="../../../work/index.php">Volver al inicio</a>';
            } else {
                echo "Cliente no encontrado.";
                echo  '<a href="../../../work/index.php">Volver al inicio</a>';
            }
        } else {
            // Crear nuevo usuario
            $clientes = new Clientes($nombre, $apellido, $direccion, $telefono, $documento, $tipo_documento);
            $clientes->guardar();
            if ($clientes->getId() !=null) {
                echo "creado cliente ha";
                echo  '<a href="../../../work/index.php">Volver al inicio</a>';
                exit;
            }
        
            echo "Error a crear el cliente.";
        
        }
    } else {
        echo "Método no permitido.";
    }


?>