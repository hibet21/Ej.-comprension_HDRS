<?php
   namespace App\Config; 

   class ResponseHTTP{
        public static $mensaje = array(
            'status' => '',
            'message' => '',
            'data' => ''
        );

        //200 OK petición correcta
        final public static function status200(string $mjs){
            self::$mensaje['status'] = 200;  //this vs self 
            self::$mensaje['message'] = $mjs;
            http_response_code(200);
            return json_encode(self::$mensaje);
        }

        //201 registros creado correctamente
        final public static function status201(string $mjs){            
            self::$mensaje['status'] = 'OK';
            self::$mensaje['message'] = $mjs;
            http_response_code(201);
            return json_encode(self::$mensaje);
        }
        //400 error en la solicitud
        final public static function status400(string $res){            
            self::$mensaje['status'] = 'ERROR';
            self::$mensaje['message'] = $res; //la variable res es el mensaje
            http_response_code(400);
            return json_encode(self::$mensaje);
        }

        //401 no autorizado
        final public static function status401(string $mjs){            
            self::$mensaje['status'] = 'ERROR';
            self::$mensaje['message'] = $mjs;
            http_response_code(401);
            return json_encode(self::$mensaje);
        }
        //404 recurso no encontrado
        final public static function status404(string $mjs){            
            self::$mensaje['status'] = 'ERROR';
            self::$mensaje['message'] = $mjs; //la variable res es el mensaje
            http_response_code(404);
            return json_encode(self::$mensaje);
        }
        //500 error interno del servidor
        final public static function status500(string $mjs){            
            self::$mensaje['status'] = 'ERROR';
            self::$mensaje['message'] = $mjs;
            http_response_code(500);
            return json_encode(self::$mensaje);
        }

    }

?>