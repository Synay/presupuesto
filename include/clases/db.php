<?php
/**
** Variables globales para la conexión
**/ 
date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, 'es_ES');

define('DIR','http://localhost/proyecto/');
define('SITEEMAIL','sy2nayblood@gmail.com');


/**
** Clase de la base de datos
**/
class DB {
    protected static $con;
    private function __construct(){
        try{
            self::$con = new PDO(
                'mysql:charset=utf8mb4;host=localhost;port=3306;dbname=tesis', 
                'root', 'root');
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_PERSISTENT, false);    
        }catch (PDOException $e){
            echo "No hemos podido conectar con la base de datos.";
            exit;
        }
    }
    /**
    ** Función que obtiene la conexiòn a la base de datos 
    **/ 
    public static function getConn(){
        if(!self::$con){
            new DB();
        }
        return self::$con;
    }

    /**
    ** Funciòn que cierra la conexión de la base de datos 
    **/
    public static function closeConn(){   
        return self::$con = null;
    }
}
?>