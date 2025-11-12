<?php 
//class conexion extiende la clase PDO, lo que significa que hereda todas las funcionalidades de PDO.
// Esto permite que la clase Conexion utilice métodos y propiedades de PDO para interactuar con bases de datos.
class Conexion extends PDO { 
 
   private $tipo_de_base = 'mysql';
   private $host = 'localhost';
   private $nombre_de_base = 'remate';
   private $usuario = 'root';
   private $contrasena = ''; 
   private $port = '3306';

   /*
      private $tipo_de_base = 'mysql';
      private $host = 'localhost';
      private $nombre_de_base = 'xxx';
      private $usuario = 'xxxxxxxxxx';
      private $contrasena = 'xxxxxxxxx';
      private $port = '3306';
   */
  //opciones de la conexión a la base de datos.
   private $options = array(
      PDO::ATTR_PERSISTENT => true, 
      PDO::ATTR_EMULATE_PREPARES => false, 
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
   );
   

   public function __construct() {
      //Sobreescribo el método constructor de la clase PDO.
      try{
         parent::__construct($this->tipo_de_base.':host='.$this->host.';port='.
         $this->port.';dbname='.$this->nombre_de_base, $this->usuario, $this->contrasena,$this->options);

        
      }catch(PDOException $e){{//el catch captura excepciones específicas, en este caso PDOException, 
                                    // que son errores relacionados con la base de datos.
         echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
         exit;
      }
   } 
   
 } 
}


?>