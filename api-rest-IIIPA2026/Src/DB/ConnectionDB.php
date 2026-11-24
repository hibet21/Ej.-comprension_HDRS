<?php

namespace App\DB;
use PDO;
use App\Config\ResponseHttp;

require __DIR__.'/dataDB.php';

class ConnectionDB{
    private static $host = '';
    private static $user = '';
    private static $password = '';

    final public static function inicializar($host,$user,$password){
        self::$host = $host;
        self::$user = $user;
        self::$password = $password;
    }

    final public static function getConnection(){
        try {
            //configurar algunas opciones de gestion de peticiones para con la BD 
            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //para que nos muestre los errores de la BD
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //para que nos retorne un array asociativo
            );
            $pdo = new PDO(self::$host,self::$user,self::$password,$opt);
            error_log('Conexión a la base de datos exitosa');
            return $pdo;
        } catch (\PDOException $e) {
            ResponseHttp::status500("Error de conexión a la base de datos: ".$e->getMessage());
        }
    }   
}
?>