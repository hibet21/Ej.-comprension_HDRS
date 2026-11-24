<?php

namespace App\Controllers;

use App\Config\ResponseHTTP;
use App\Models\UserModel;

class UserController{
    private $method; //get post put delete
    private $data; //datos que se envian en el body de la peticion
    private $route; //ruta de la peticion user, auth, productos, ventas, inventario
    private $params; //parametros que se envian en la ruta de la peticion id de ususario
    private $headers; //cabeceras de la peticion (authorization, content-type, accept, etc)

    //expresiones regulares para validar los datos que se envian en el body de la peticion
    public static $validar_rol = '/^[1,2,3]{1,1}$/'; //validando que el rol sea 1, 2 o 3 y que solo sea un caracter
    public static $validar_numero = '/^[0-9]+$/'; //validando que el numero sea de 1 a 10 digitos
    public static $validar_texto = '/^[a-zA-Z]+$/'; //validando que el texto solo contenga letras

    //3 tipos de constructores en POO?
    public function __construct($method, $data, $route, $params, $headers){
        $this->method = $method;
        $this->data = $data;
        $this->route = $route;
        $this->params = $params;
        $this->headers = $headers;
    }
    //metodo estatico para guardar un usuario
    final public function UserSave($endpoint){ //post  
        if($endpoint == $this->route && $this->method == 'post'){ //verificando que el endpoint sea el mismo que el de la ruta y que el metodo sea post
            //validamos que los campos no vengan vacios 
            if(empty($this->data['nombre']) || empty($this->data['dni']) || empty($this->data['email']) || empty($this->data['rol']) || empty($this->data['IDToken']) || empty($this->data['clave']) || empty($this->data['ConfirmaClave']) || empty($this->data['fecha'])){
                echo ResponseHTTP::status400('Para poder continuar debe llenar todos los campos!');              
            }else if(!preg_match(self::$validar_texto,$this->data['nombre'])){
                echo ResponseHTTP::status400('El campo nombre debe contener solo texto!');
            }else if(!preg_match(self::$validar_numero,$this->data['dni'])){
                echo ResponseHTTP::status400('El campo DNI debe contener solo numeros!');
            }else if(!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)){
                echo ResponseHTTP::status400('El email debe tener el formato correcto!');
            }else if(!preg_match(self::$validar_rol, $this->data['rol'])){
                echo ResponseHTTP::status400('El rol del usuario no es correcto!');
            }else{
                //que instrucciones van aqui? una vez valida la información
                //mandanla al modelo para hacer la bajada a la BD
                new UserModel($this->data);
                echo UserModel::UserSave();
            }
        }
    }
}
?>