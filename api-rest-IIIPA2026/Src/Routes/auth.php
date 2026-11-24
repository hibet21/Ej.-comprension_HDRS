<?php
use App\Config\Security; //cargando del nombre de espacios Config la clase Security
echo 'clave secreta:' . Security::secretKey();

use App\DB\ConnectionDB; //cargando del nombre de espacios DB la clase ConnectionDB
ConnectionDB::getConnection(); //llamando al metodo getConnection de la clase ConnectionDB


?>