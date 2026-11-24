<?php

use Dotenv\Dotenv;
use App\DB\ConnectionDB;
use App\Config\Errorlogs;

Errorlogs::activar_error_logs(); //inicializando el log de errores

$donenv = Dotenv::createImmutable(dirname(__DIR__, 2)); //cargando el archivo .env
$donenv->load(); //cargando las variables de entorno del archivo .env

$data = array(
    "IP" => $_ENV['IP'],
    "USER" => $_ENV['USER'],
    "PASSWORD" => $_ENV['PASSWORD'],
    "DB" => $_ENV['DB'],
    "PORT" => $_ENV['PORT']
);

$host = 'mysql:host='.$data['IP'].';port='.$data['PORT'].';dbname='.$data['DB'].';';
ConnectionDB::inicializar($host,$data['USER'],$data['PASSWORD']);
?>