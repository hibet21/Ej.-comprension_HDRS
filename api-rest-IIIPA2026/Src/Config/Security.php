<?php
namespace App\Config;
use Dotenv\Dotenv;
use Firebase\JWT\JWT; //para generar nuestro JWT https://github.com/firebase/php-jwt
use Firebase\JWT\Key;

class Security{
    private static $jwt_data;//Propiedad para guardar los datos decodificados del JWT 

    //retornar la firma de JWT (Clave Secreta definida en el archivo .env)
    final public static function secretKey(){
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2)); //cargando el archivo .env
        $dotenv->load(); //cargando las variables de entorno del archivo .env
        return $_ENV['SECRET_KEY']; //retornando la variable de entorno SECRET_KEY
    }

   /*METODO para Encriptar la contraseña del usuario*/
    final public static function createPassword($pass)
    {
        $pass = password_hash($pass,PASSWORD_DEFAULT); //metodo para encriptar mediante hash
        //recibe 2 parametros el primero el la cadena (pass) y el segundo es el metodo de encriptación (por defecto BCRIPT)
        return $pass;
    }

    /*Metodo para Validar que las contraseñas coincidan o sean iguales*/
    
    final public static function validatePassword($pw,$pwh)
    {
        if (password_verify($pw,$pwh)) {
            return  TRUE;
        } else {
            error_log('La contraseña es incorrecta');
           return  FALSE;
        }   
        
    }

    /*MEtodo para crear JWT*/
    /*PARAM: 1.	SECRET_KEY
             2.	ARRAY con la data que queremos encriptar*/

    final public static function createTokenJwt(string $key , array $data)
    {
        $payload = array ( //Cuerpo del JWT
            "iat" => time(),  //clave que almacena el tiempo en el que creamos el JWT
            "exp" => time() + (60*60*6), //clave que almacena el tiempo actual en segundos que expira el JWT
            //si solo colocamos 10 entonces expirara en 10 segundos 60 seg*60 min*1 hr
            "data" => $data //clave que almacena la data encriptada
        );
        
        //creamos el JWT recibe varios parametros pero nos interesa el payload y la key en el metodo encode de JWT
        $jwt = JWT::encode($payload, $key, 'HS256');
       // print_r($jwt);
        return $jwt;
    }

    /*Validamos que el JWT sea correcto*/
    //recibimos dos parametros uno es un array y otro es la KEY para decifrar nuestro JWT
    final public static function validateTokenJwt(array $token, string $key)
    {
       // print_r($token);
        //usaremos el metodo getallheader() el que Recupera todas las cabeceras de petición HTTP
        //buscaremos la cabecera Autorization, sino existe la detiene y manda un mensaje de error
        //thunterClient autorization 
        //postman Autorization
        if (!isset($token['Authorization'])) {
            //echo "El token de acceso en requerido";
            die(json_encode(ResponseHttp::status400("Para proceder el token de acceso es requerido!")));            
            exit;
        }
        try {
            //recibimos el token de acceso y creamos el array 
            //se veria mas o menos asi 
            // $token = "Bearer token"; posicion 0 y posicion 1
            $jwt = explode(" " ,$token['Authorization']);

            /*
            Auth = "Bearer lhglkfdh.jhdslhg.jkshfkjhs"
            auth = {
                'Bearer',  //posicion 0
                'lhglkfdh.jhdslhg.jkshfkjhs' //posicion 1
            }
            */

            $data = JWT::decode($jwt[1], new Key($key, 'HS256')); //param1: token, param2: clave y algoritmo
            //necesitamos crear un array asociativo para poder retornarlo y que sea mas facil recorrerlo
            //1. definimos el atributo 
            //private static $jwt_data;//Propiedad para guardar los datos decodificados del JWT 

            self::$jwt_data = $data; //le pasamos el jwt decodificado y lo retornamos
            return $data;
            exit;
        } catch (\Exception $e) {
            error_log('Token invalido o expiro'. $e);
            die(json_encode(ResponseHttp::status401('Token invalido o ha expirado'))); //funcion que manda un mj y termina ejecucion 
        }
    }

    /*Devolver los datos del JWT decodificados en un array asociativo*/
    final public static function getDataJwt()
    {
        $jwt_decoded_array = json_decode(json_encode(self::$jwt_data),true);
        return $jwt_decoded_array['data'];
        exit;
    }


}
?>