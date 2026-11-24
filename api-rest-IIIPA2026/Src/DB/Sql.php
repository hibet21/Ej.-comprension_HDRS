<?php
    //aprender como generar querys dinamicos en PHP
    //aprender como validar si un registro existe sin importar la tabla que deba consultar
    //y la estructura de consulta

    namespace App\DB; //definiendo el nombre de espacios DB
    use App\Config\ResponseHTTP; //cargando del nombre de espacios Config la clase ResponseHTTP
    use App\Config\Errorlogs; //cargando del nombre de espacios Config la clase Errorlogs

    Errorlogs::activar_error_logs(); //llamando a la clase Errorlogs y al metodo estatico activar_error_logs
    class Sql extends ConnectionDB{ //heredando de la clase Conexion
        public static function verificarRegistro($sql, $condicion, $parametros){ 
            try{
                $conn = self::getConnection(); //llamando al metodo estatico getConnection de la clase ConnectionDB
                $query = $conn->prepare($sql); //preparando la consulta SQL
                $query->execute([
                    $condicion => $parametros
                ]); //ejecutando la consulta SQL con los parametros enviados

                $res = ($query->rowCount() > 0) ? true : false; //verificando si el registro existe
                return $res; //retornando el resultado de la verificacion
            }catch(\PDOException $e){
                error_log('Error en la verificacion del registro: ' . $e->getMessage()); //registrando el error en el archivo de log
                echo ResponseHTTP::status500('Error en la verificacion del registro!'); //llamando a la clase ResponseHTTP y al metodo estatico status500
                exit;
            }
        }

        //QUE OTRAS ACCIONES PUEDO DESARROLLAR D EMANERA DINAMICA CON ESTA CLASE?

    }
?>