<?php
namespace App\Config;
date_default_timezone_set('America/Tegucigalpa'); //establece la zona horaria predeterminada para todas las funciones de fecha y hora en un script
class Errorlogs{
    public static function activar_error_logs(){
        error_reporting(E_ALL); //reporta todos los errores
        ini_set('ignore_repeated_errors', 1); //ignora errores repetidos
        ini_set('display_errors', 0); //muestra los errores en pantalla
        ini_set('log_errors', 1); //registra los errores en un archivo de log
        ini_set('error_log', dirname(__DIR__).'/Logs/php-error.log'); //ruta del archivo de log 
    }
}

?>